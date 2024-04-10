<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use PhpParser\Node\Stmt\Return_;

class ForgotPasswordController extends Controller
{
  use AuthenticatesUsers;

    public function showforgotPassword()
    {
      return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request, User $user)
    {
      $request->validate([
        'email' => 'required|email|exists:users'
      ]);

      $user = $user->where('email', $request->email)->first();
      Session::put('forgot-password-email', $user->email);
      $data = [
        'name'  => $user->name,
        'email' => $user->email,
        'url' => URL::temporarySignedRoute(
          'reset-password', now()->addMinutes(20), ['email' => $user->email])
      ];

      try{
        Mail::send('emails.reset-password', ['data'=> $data], function($message) use ($user) {
          $message->to($user->email, 'Michael')
          ->subject('Laravel Test Mail');
          $message->from('laravel@gmail.com','Test Mail');
          });
        
          return back()->with([
            'message' => 'Reset Password link has been sent to your email address'
          ]);

      }catch(\Exception $e){
        return redirect()->back()
        ->withInput(['email' => $user->email])
        ->withErrors( [$this->username() => trans('auth.contact')] );
      }
    }

    public function showResetPassword(Request $request)
    {
      if (!$request->hasValidSignature()) {
        abort(401);
      }
      return view('auth.show-reset-password');
    }

    public function updatePassword(Request $request, User $userModel)
    { 
      $request->validate([
        'password' => 'required|confirmed'
      ]);

      $email = Session::get('forgot-password-email');
      $user = $userModel->where('email', $email)->first();

      if(Hash::check($request->password, $user->password)){
        return back()
          ->withErrors(['password' => 'Password must different from current password']);
      }

      $user = $userModel->where('email', $email)->update([
        'password' => Hash::make($request->password)
      ]);

      return back()->with(['message' => 'Success', 'status' => true]);

    }
}
