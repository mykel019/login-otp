<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;

class SignUpController extends Controller
{
    public function signUp()
    {
      return view('auth.signup');
    }

    public function register(RegisterRequest $request)
    {
      try{
        User::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => bcrypt($request->password)
        ]);
  
        return back()->with([
          'message' => 'SignUp Success'
        ]);
    
      }catch(\Exception $e){
        return back()->with([
          'error' => trans('auth.contact')
        ]);
      }
    }
}
