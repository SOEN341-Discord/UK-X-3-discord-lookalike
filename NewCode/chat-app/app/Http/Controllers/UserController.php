<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Channel;
use App\Events\MessageSent;
use App\Events\MessageBroadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function solveForm(Request $request)
    {

        return view("profile.solveRiddle", [
            'user' => $request->user(),
        ]);

    }

    public function solveRiddle(Request $request)
    {
        $riddleAnswer = $request->input('riddle_answer');
        
        if ($riddleAnswer == '22') {  
            $user = auth()->user();  

            // Update the user to be an admin
            $user->update(['is_admin' => true]);

            // Remove the user member status (can't be both)
            $user->update(["is_member" => false]);

            return redirect()->route('profile.edit')->with('success', 'Congratulations, you are now an admin!');
        }

        return back()->withErrors(['riddle_answer' => 'Incorrect answer. Try again!']);
    }
}

