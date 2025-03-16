<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.index', compact('users'));
    }

    public function update(Request $request, User $user)
     {
         
         // Optional: Validate if necessary (e.g., ensure only booleans are passed)
         $request->validate([
             'is_admin' => 'nullable|boolean',
             'is_member' => 'nullable|boolean',
         ]);
 
         // Check if the user is being promoted from member to admin or vice versa
         if ($request->has('is_admin') && $request->has('is_member')) {
             $user->update([
                 'is_admin' => true,  // Promote to admin
                 'is_member' => false, // Optionally, you can make sure the user is not a member anymore
             ]);
         } elseif ($request->has('is_member')) {
             $user->update([
                 'is_member' => true,  // Ensure the user is a member (no change for admins in this case)
             ]);
         } elseif ($request->has('is_admin')) {
             $user->update([
                 'is_admin' => true,  // Promote to admin
                 'is_member' => false, // Make sure the user is no longer a member (optional)
             ]);
         }
 
         return redirect()->route('admin.index')->with('success', 'User updated successfully.');
     }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.index')->with('success', 'User deleted successfully.');
    }
}
