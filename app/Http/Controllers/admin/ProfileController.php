<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function myProfile()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }
    public function editProfile()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

   public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation
        $validated = $request->validate([
            'username' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                Rule::unique('users')->ignore($user->id),
            ],

            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',

            // image
            'profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle image upload (SAME as user update)
        if ($request->hasFile('profile')) {

            // delete old image
            if ($user->profile && file_exists(public_path('uploads/users/'.$user->profile))) {
                unlink(public_path('uploads/users/'.$user->profile));
            }

            // new image name
            $imageName = time().'_'.uniqid().'.'.$request->profile->extension();

            // move file
            $request->profile->move(public_path('uploads/users'), $imageName);

            $validated['profile'] = $imageName;
        }

        

        // Update user
        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

}

