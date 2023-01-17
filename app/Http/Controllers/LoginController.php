<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        // dd(empty($data['email']) || empty($data['password']));
        if (empty($data['email']) || empty($data['password'])) {
            return redirect()->back()->withErrors(['msg' => 'Enter your email and password']);
        }
        if (Auth::attempt($data)) {
            return redirect()->route('user.index');
        } else {
            return redirect()->back()->withErrors(['msg' => 'Invalid username or password']);
        }
    }
}
