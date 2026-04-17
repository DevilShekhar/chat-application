<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $users = User::where('role', '!=', 'client')->latest()->get();
        return view('admin.users.index', compact('users'));
    }  

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits_between:10,15|unique:users,phone',
            'password' => 'required|min:6|confirmed',
            'gender' => 'required|string',
            'address' => 'required|string|max:255',
            'education' => 'required|string|max:255',
            'role' => 'required|in:admin,hr,team_member,client',
            'account_status' => 'nullable|in:0,1',
            'profile' => 'required|image|mimes:jpg,webp,jpeg,png|max:2048',
        ]);
        $validated['account_status'] = $request->account_status ?? 0;
        // Upload Image
        if ($request->hasFile('profile')) {
            $imageName = time().'_'.uniqid().'.'.$request->profile->extension();
            $request->profile->move(public_path('uploads/users'), $imageName);
            $validated['profile'] = $imageName;
        }

        // Hash Password
        $validated['password'] = Hash::make($request->password);

        // Default values
        $validated['otp_verified'] = 0;

        // Assign Unique Color
        $validated['bg_colors'] = $this->generateUniqueColor();
        // Save User
        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }
    private function generateUniqueColor()
    {
        do {
            $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        } while (User::where('bg_colors', $color)->exists());

        return $color;
    }
   

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validation
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'phone' => [
                'required',
                Rule::unique('users')->ignore($id),
            ],
            'password' => 'nullable|min:6|confirmed',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string|max:255',
            'education' => 'required|string|max:255',
            'role' => 'required|in:admin,hr,team_member,client',
            'account_status' => 'required|in:0,1',
            'profile' => 'required|image|mimes:jpg,webp,jpeg,png|max:2048',
        ]);

        // Upload new image
        if ($request->hasFile('profile')) {

            if ($user->profile && file_exists(public_path('uploads/users/'.$user->profile))) {
                unlink(public_path('uploads/users/'.$user->profile));
            }

            $imageName = time().'_'.uniqid().'.'.$request->profile->extension();
            $request->profile->move(public_path('uploads/users'), $imageName);

            $validated['profile'] = $imageName;
        }

        // Update password only if entered
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        // Assign color ONLY if not already set
        if (!$user->bg_colors) {
            $validated['bg_colors'] = $this->generateUniqueColor();
        }

        // Update user
        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    
   public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->account_status = 1;
        $user->save();
        return redirect()->route('users.index') ->with('success', 'User deactivated successfully.');
    }

    /**
     * Display a listing of the resource.
     */
    public function Clientindex()
    {
        $users = User::where('role', 'client')->latest()->get();
        return view('admin.clients.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function Clientcreate()
    {
         return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function Clientstore(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'founder_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits_between:10,15|unique:users,phone',
            'password' => 'required|min:6|confirmed',
            'website_link' => 'required|url',
            'company_address' => 'required|string|max:255',
            'company_sector' => 'required',
            'company_sector_other' => 'required_if:company_sector,Other',
            'profile' => 'required|image|mimes:jpg,webp,jpeg,png|max:2048',
        ]);

        // Handle "Other" sector
        $sector = $request->company_sector === 'Other'
            ? $request->company_sector_other
            : $request->company_sector;

        $validated['company_sector'] = $sector;

        // Set username from company name
        $validated['username'] = $request->company_name;

        // Default values
        $validated['role'] = 'client';
        $validated['account_status'] = 0;
        $validated['otp_verified'] = 0;

        // Upload Image
        if ($request->hasFile('profile')) {
            $imageName = time().'_'.uniqid().'.'.$request->profile->extension();
            $request->profile->move(public_path('uploads/users'), $imageName);
            $validated['profile'] = $imageName;
        }

        // Hash Password
        $validated['password'] = Hash::make($request->password);

        // Unique Color
        $validated['bg_colors'] = $this->generateUniqueColor();

        // Save
        User::create($validated);

    return redirect()->route('clients.index')->with('success', 'Client created successfully');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function Clientedit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.clients.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function Clientupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'founder_name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],

            'phone' => [
                'required',
                'digits_between:10,15',
                Rule::unique('users')->ignore($id),
            ],

            'website_link' => 'required|url',
            'company_address' => 'required|string|max:255',

            'company_sector' => 'required',
            'company_sector_other' => 'required_if:company_sector,Other',

            'account_status' => 'required|in:0,1',

            'profile' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle sector (Other case)
        $sector = $request->company_sector === 'Other'
            ? $request->company_sector_other
            : $request->company_sector;

        $validated['company_sector'] = $sector;

        // username = company_name
        $validated['username'] = $request->company_name;

        // Upload image
        if ($request->hasFile('profile')) {

            if ($user->profile && file_exists(public_path('uploads/users/'.$user->profile))) {
                unlink(public_path('uploads/users/'.$user->profile));
            }

            $imageName = time().'_'.uniqid().'.'.$request->profile->extension();
            $request->profile->move(public_path('uploads/users'), $imageName);

            $validated['profile'] = $imageName;
        }

        // Update
        $user->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    
   public function Clientdestroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->account_status = 1;
        $user->save();
        return redirect()->route('users.index') ->with('success', 'User deactivated successfully.');
    }
}
