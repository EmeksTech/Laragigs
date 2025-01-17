<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //shoow Register form
    public function create()
    {
        return view('users.register');
    }

    //create new user
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:6'
        ]);

        $formFields['password'] = bcrypt($formFields['password']);

        $user = User::create($formFields);

        //login
        auth()->login($user);

        return redirect('/')->with('message', 'User has been created and logged in');
    }

    //logout
    public function logout(Request $request)
    {
        auth()->logout();
       // $request->session->invalidate();


        return redirect('/')->with('message', 'You have been logged out');

    }

    //show login form
    public function login(){
        return view('users.login');
    }

    //Authenticate user
    public function authenticate(Request $request)

    {
        $formFields = $request->validate([

            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        if(auth()->attempt($formFields)){
            $request->session()->regenerate(); 
            return redirect('/')->with('message', 'You are now logged in!');

        }

        //login


        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');

    }
}
