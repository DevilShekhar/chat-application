<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class GroupsController extends Controller
{
    /** 
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereNotIn('role', ['admin', 'hr'])->get();
        $groups = Group::withCount('members')->latest()->get();

        return view('admin.groups.index', compact('users', 'groups'));
    }
    public function create()
    {
        $users = User::whereIn('role', ['team_member', 'client'])->get();

        $groups = Group::withCount('members')->latest()->get();

        return view('admin.groups.create', compact('users', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_name' => 'required|unique:groups,group_name',
            'title' => 'required|string|max:255',
            'group_profile' => 'nullable|image|mimes:jpg,jpeg,webp,png|max:2048',
            'members' => 'required|array'
        ]);

        // Upload Group Profile Image
        if ($request->hasFile('group_profile')) {
            $imageName = time().'_'.uniqid().'.'.$request->group_profile->extension();
            $request->group_profile->move(public_path('uploads/groups'), $imageName);

            $validated['group_profile'] = $imageName;
        }

        // Create Group
        $group = Group::create([
            'group_name' => $validated['group_name'],
            'title' => $validated['title'],
            'group_profile' => $validated['group_profile'] ?? null,
            'group_type' => $request->group_type,
            'created_by' => Auth::id(),
            'is_active' => true,
        ]);

        $members = [];

        // Add selected users
        foreach ($request->members as $memberId) {
            $members[$memberId] = ['role' => 'member'];
        }

        // Add creator
        $members[Auth::id()] = ['role' => 'member'];

        // Add admin + hr automatically
        $adminsAndHr = User::whereIn('role', ['admin', 'hr'])->pluck('id');

        foreach ($adminsAndHr as $userId) {
            $members[$userId] = ['role' => 'member'];
        }

        // Attach members
        $group->members()->sync($members);

        return redirect()->route('groups.index')->with('success', 'Group created successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $group = Group::with(['members', 'creator'])->findOrFail($id);

        return view('admin.groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = auth()->user()->role;

        if ($role === 'admin') {

            $group = Group::with('members')->findOrFail($id);

            // Exclude admin from available users
            $users = User::withoutGlobalScopes()
                        ->where('role', '!=', 'admin')
                        ->get();

            // Remove admin from members (only for UI logic)
            $group->setRelation('members', $group->members->filter(function ($member) {
                return $member->role !== 'admin';
            }));

        } else {

            $group = Group::with(['members' => function ($q) {
                $q->whereIn('users.role', ['team_member', 'client']);
            }])->findOrFail($id);

            $users = User::whereIn('role', ['team_member', 'client'])->get();
        }

        return view('admin.groups.edit', compact('group', 'users'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $validated = $request->validate([
            'group_name' => 'required|unique:groups,group_name,' . $group->id,
            'title' => 'required|string|max:255',
            'group_profile' => 'nullable|image|mimes:jpg,webp,jpeg,png|max:2048',
        ]);

        // ======================
        // IMAGE UPLOAD
        // ======================
        if ($request->hasFile('group_profile')) {

            if ($group->group_profile && file_exists(public_path('uploads/groups/' . $group->group_profile))) {
                unlink(public_path('uploads/groups/' . $group->group_profile));
            }

            $imageName = time().'_'.uniqid().'.'.$request->group_profile->extension();
            $request->group_profile->move(public_path('uploads/groups'), $imageName);

            $validated['group_profile'] = $imageName;
        }

        // ======================
        // UPDATE GROUP
        // ======================
        $group->update([
            'group_name' => $validated['group_name'],
            'title' => $validated['title'],
            'group_profile' => $validated['group_profile'] ?? $group->group_profile,
            'group_type' => $request->group_type,
        ]);

        // ======================
        // MEMBERS LOGIC
        // ======================

        // Selected members from form
        $members = $request->members ?? [];

        // Remove empty values
        $members = array_filter($members);

        // Get all admin users (IMPORTANT)
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();

        // Merge admin users (so they NEVER get removed)
        $members = array_unique(array_merge($members, $adminIds));

        // Sync members (removes others automatically)
        $group->members()->sync($members);

        // ======================
        // REDIRECT
        // ======================
        return redirect()->route('groups.index')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
