<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\GroupMessageRead;
use App\Models\Group;
use App\Events\MessageSent;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    // ================= DASHBOARD =================
   public function index(Request $request)
    {
        $authUser = auth()->user();
        $authId = $authUser->id;

        $users = collect();
        $groups = collect();

        // ================= ADMIN =================
        if ($authUser->role === 'admin') {

            // ✅ PRIVATE USERS (NO CHANGE)
            $users = User::where('id', '!=', $authId)
                ->withCount(['messages as unread_count' => function ($q) use ($authId) {
                    $q->where('receiver_id', $authId)
                    ->where('type', 'private')
                    ->whereNull('read_at');
                }])
                ->get();

            // ✅ GROUPS (FIXED)
            $groups = Group::withCount(['messages as unread_count' => function ($q) use ($authId) {

                $q->where('type', 'group')
                ->where('sender_id', '!=', $authId)

                // ✅ IMPORTANT FIX
                ->whereNotIn('id', function ($sub) use ($authId) {
                    $sub->select('message_id')
                        ->from('group_message_reads')
                        ->where('user_id', $authId);
                });

            }])->get();
        }

        // ================= HR =================
        elseif ($authUser->role === 'hr') {

            $users = User::where('id', '!=', $authId)
                ->withCount(['messages as unread_count' => function ($q) use ($authId) {
                    $q->where('receiver_id', $authId)
                    ->where('type', 'private')
                    ->whereNull('read_at');
                }])
                ->get();

            $groups = Group::whereHas('members', function ($q) use ($authId) {
                    $q->where('user_id', $authId);
                })
                ->withCount(['messages as unread_count' => function ($q) use ($authId) {

                    $q->where('type', 'group')
                    ->where('sender_id', '!=', $authId)

                    // ✅ FIX
                    ->whereNotIn('id', function ($sub) use ($authId) {
                        $sub->select('message_id')
                            ->from('group_message_reads')
                            ->where('user_id', $authId);
                    });

                }])
                ->get();
        }

        // ================= TEAM MEMBER =================
        elseif ($authUser->role === 'team_member') {

            if ($request->search) {
                $users = User::where('email', 'like', '%' . $request->search . '%')
                    ->where('id', '!=', $authId)
                    ->get();
            } else {
                $users = User::whereHas('messages', function ($q) use ($authId) {
                    $q->where(function ($query) use ($authId) {
                        $query->where('sender_id', $authId)
                            ->orWhere('receiver_id', $authId);
                    });
                })
                ->where('id', '!=', $authId)
                ->distinct()
                ->get();
            }

            foreach ($users as $user) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $authId)
                    ->where('type', 'private')
                    ->whereNull('read_at')
                    ->count();
            }

            $groups = Group::whereHas('members', function ($q) use ($authId) {
                $q->where('user_id', $authId);
            })->get();

            foreach ($groups as $group) {

                // ✅ FIXED GROUP UNREAD
                $group->unread_count = Message::where('group_id', $group->id)
                    ->where('type', 'group')
                    ->where('sender_id', '!=', $authId)
                    ->whereNotIn('id', function ($sub) use ($authId) {
                        $sub->select('message_id')
                            ->from('group_message_reads')
                            ->where('user_id', $authId);
                    })
                    ->count();
            }
        }

        // ================= CLIENT =================
        elseif ($authUser->role === 'client') {

            $groups = Group::whereHas('members', function ($q) use ($authId) {
                $q->where('user_id', $authId);
            })->get();

            foreach ($groups as $group) {

                // ✅ FIXED
                $group->unread_count = Message::where('group_id', $group->id)
                    ->where('type', 'group')
                    ->where('sender_id', '!=', $authId)
                    ->whereNotIn('id', function ($sub) use ($authId) {
                        $sub->select('message_id')
                            ->from('group_message_reads')
                            ->where('user_id', $authId);
                    })
                    ->count();
            }
        }

        return view('admin.chat.index', compact('users', 'groups'));
    }
    public function searchUsers(Request $request)
    {
        $authId = auth()->id();
        $search = $request->search;

        $users = User::where('id', '!=', $authId)
            ->where('username', 'like', '%' . $search . '%')
            ->get();

        return response()->json($users);
    }
    // ================= GROUP CHAT =================
    public function groupChat($groupId)
    {
        $authId = auth()->id();

        // MARK AS READ FIRST
        Message::where('group_id', $groupId)
            ->where('type', 'group')
            ->where('sender_id', '!=', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // USERS WITH UNREAD COUNT
        $users = User::where('id', '!=', $authId)
            ->withCount(['messages as unread_count' => function ($q) use ($authId) {
                $q->where('receiver_id', $authId)
                ->where('type', 'private')
                ->whereNull('read_at');
            }])
            ->get();

        // GROUPS WITH UNREAD COUNT
        $groups = Group::whereHas('members', function ($q) use ($authId) {
                $q->where('user_id', $authId);
            })
            ->withCount(['messages as unread_count' => function ($q) use ($authId) {
                $q->where('type', 'group')
                ->where('sender_id', '!=', $authId)
                ->whereNull('read_at');
            }])
            ->get();

        // CURRENT GROUP
        $group = Group::findOrFail($groupId);

        // LOAD MESSAGES
        $messages = Message::where('group_id', $groupId)
            ->where('type', 'group')
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.chat.index', compact(
            'users',
            'groups',
            'messages',
            'group'
        ))->with([
            'type' => 'group',
            'group_id' => $groupId
        ]);
    }
    // ================= SEND GROUP MESSAGE =================
    public function sendGroupMessage(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'message' => 'required|string'
        ]);

        $message = Message::create([
            'group_id' => $request->group_id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'type' => 'group',
            'is_deleted' => false
        ]);

        // BROADCAST EVENT (IMPORTANT)
         broadcast(new MessageSent($message))->toOthers();

        return response()->json($message->load('sender'));
    }

    // ================= PRIVATE CHAT =================
    public function privateChat($id)
    {
        $authId = auth()->id();

        // MARK AS READ FIRST
        Message::where('receiver_id', $authId)
            ->where('sender_id', $id)
            ->where('type', 'private')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // USERS WITH UNREAD COUNT
        $users = User::where('id', '!=', $authId)
            ->withCount(['messages as unread_count' => function ($q) use ($authId) {
                $q->where('receiver_id', $authId)
                ->where('type', 'private')
                ->whereNull('read_at');
            }])
            ->get();

        // GROUPS WITH UNREAD COUNT
        $groups = Group::whereHas('members', function ($q) use ($authId) {
                $q->where('user_id', $authId);
            })
            ->withCount(['messages as unread_count' => function ($q) use ($authId) {
                $q->where('type', 'group')
                ->where('sender_id', '!=', $authId)
                ->whereNull('read_at');
            }])
            ->get();

        // CURRENT USER
        $chatUser = User::findOrFail($id);

        // LOAD PRIVATE MESSAGES
        $messages = Message::where('type', 'private')
            ->where('is_deleted', false)
            ->where(function ($q) use ($id, $authId) {

                $q->where(function ($q2) use ($id, $authId) {
                    $q2->where('sender_id', $authId)
                    ->where('receiver_id', $id);
                });

                $q->orWhere(function ($q2) use ($id, $authId) {
                    $q2->where('sender_id', $id)
                    ->where('receiver_id', $authId);
                });

            })
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.chat.index', compact(
            'users',
            'groups',
            'messages',
            'chatUser'
        ))->with([
            'type' => 'private',
            'user_id' => $id
        ]);
    }

    // ================= SEND PRIVATE MESSAGE =================
    public function sendPrivateMessage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->user_id,
            'message' => $request->message,
            'type' => 'private',
            'is_deleted' => false
        ]);

        $message->load('sender');
        $message->file_url = null;

         broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
    public function sendFile(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|max:5120|mimes:jpg,jpeg,png,webp,gif,svg,pdf,doc,docx,zip,sql,txt,json,xml,csv,xls,xlsx,ppt,pptx'
        ]);

        $manager = new ImageManager(new Driver());
        $messages = [];

        foreach ($request->file('files') as $file) {

            // ✅ SAFE CHECK
            if (!$file->isValid()) {
                return response()->json([
                    'error' => 'File upload failed',
                    'code' => $file->getError(),
                    'message' => $file->getErrorMessage()
                ]);
            }

            $extension = strtolower($file->getClientOriginalExtension());
            $filename = uniqid() . '.' . $extension;
            $path = 'chat_files/' . $filename;
            $fullPath = storage_path('app/public/' . $path);

            // ================= IMAGE =================
            if (in_array($extension, ['jpg','jpeg','png','webp'])) {

                $image = $manager->read($file);
                $image->scaleDown(width: 800);

                $compressed = $image->toJpeg(quality: 30);

                Storage::disk('public')->put($path, (string) $compressed);
            }

            // ================= PDF =================
            elseif ($extension === 'pdf') {

                $tempPath = $file->getRealPath();

                $cmd = 'gswin64c -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 '
                    . '-dPDFSETTINGS=/screen '
                    . '-dNOPAUSE -dQUIET -dBATCH '
                    . '-sOutputFile="' . $fullPath . '" '
                    . '"' . $tempPath . '"';

                exec($cmd);

                // fallback (FIXED)
                if (!file_exists($fullPath) || filesize($fullPath) == 0) {
                    Storage::disk('public')->putFileAs('chat_files', $file, $filename);
                }
            }

            // ================= DOC / DOCX =================
            elseif (in_array($extension, ['doc','docx'])) {

                // ✅ FIXED (NO file_get_contents)
                Storage::disk('public')->putFileAs('chat_files', $file, $filename);

                // 🔥 safe recompression
                try {
                    $zip = new \ZipArchive();

                    if ($zip->open($fullPath) === TRUE) {

                        $tempZip = $fullPath . '.tmp';

                        $newZip = new \ZipArchive();
                        if ($newZip->open($tempZip, \ZipArchive::CREATE) === TRUE) {

                            for ($i = 0; $i < $zip->numFiles; $i++) {
                                $stat = $zip->statIndex($i);
                                $content = $zip->getFromIndex($i);

                                $newZip->addFromString($stat['name'], $content);
                            }

                            $newZip->close();
                            $zip->close();

                            if (file_exists($tempZip) && filesize($tempZip) < filesize($fullPath)) {
                                unlink($fullPath);
                                rename($tempZip, $fullPath);
                            } else {
                                if (file_exists($tempZip)) unlink($tempZip);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // ignore
                }
            }

            // ================= OTHER FILES =================
            else {
                // ✅ FIXED (NO file_get_contents)
                Storage::disk('public')->putFileAs('chat_files', $file, $filename);
            }

            // ================= SAVE MESSAGE =================
            $message = new Message();
            $message->sender_id = auth()->id();
            $message->receiver_id = $request->user_id ?? null;
            $message->group_id = $request->group_id ?? null;
            $message->type = $request->group_id ? 'group' : 'private';

            $message->message = '';
            $message->file_path = $path;
            $message->file_name = $file->getClientOriginalName();
            $message->is_deleted = false;
            $message->save();

            $message->load('sender');
            $message->file_url = asset('storage/' . $path);

            $messageArray = $message->toArray();
            $messageArray['file_url'] = $message->file_url;

            broadcast(new MessageSent($message))->toOthers();

            $messages[] = $messageArray;
        }

        return response()->json($messages);
    }
    public function startChat($id)
    {
        $authId = auth()->id();

        // check if already chat exists
        $existing = Message::where(function ($q) use ($authId, $id) {
            $q->where('sender_id', $authId)
            ->where('receiver_id', $id);
        })->orWhere(function ($q) use ($authId, $id) {
            $q->where('sender_id', $id)
            ->where('receiver_id', $authId);
        })->first();

        // if no chat → create dummy message (optional)
        if (!$existing) {
            Message::create([
                'sender_id' => $authId,
                'receiver_id' => $id,
                'message' => 'Chat started',
            ]);
        }

        return redirect()->route('chat.index');
    }
    public function updateMessage(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
            'message' => 'required|string|max:5000'
        ]);

        $message = Message::where('id', $request->message_id)
            ->where('sender_id', auth()->id()) // only sender can edit
            ->firstOrFail();

        //block file edit
        if ($message->file_path) {
            return response()->json(['error' => 'Cannot edit file message'], 403);
        }

        // (optional) 15 min limit
        if ($message->created_at->diffInMinutes(now()) > 15) {
            return response()->json(['error' => 'Edit time expired'], 403);
        }

        $message->message = $request->message;
        $message->is_edited = true;
        $message->edited_at = now();
        $message->save();

        $message->load('sender');

        // realtime update
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
    public function markAsRead(Request $request)
    {
        Message::where('sender_id', $request->user_id)
            ->where('receiver_id', auth()->id())
            ->where('type', 'private') // ✅ safety
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
   public function markGroupAsRead(Request $request)
    {
        $authId = auth()->id();
        $groupId = $request->group_id;

        // ✅ get unread messages only
        $messages = DB::table('messages')
            ->where('group_id', $groupId)
            ->where('type', 'group')
            ->where('sender_id', '!=', $authId)
            ->whereNotIn('id', function ($q) use ($authId) {
                $q->select('message_id')
                ->from('group_message_reads')
                ->where('user_id', $authId);
            })
            ->pluck('id');

        // ✅ bulk insert (FAST 🚀)
        $data = $messages->map(function ($id) use ($authId) {
            return [
                'message_id' => $id,
                'user_id' => $authId,
                'read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        DB::table('group_message_reads')->insertOrIgnore($data);

        return response()->json(['success' => true]);
    }
}