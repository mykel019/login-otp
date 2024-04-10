<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Seshac\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PhpOption\None;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
      $this->middleware('guest')->except('logout');
    }
    public function login()
    {
      return view('auth.login');
    }

    public function authenticate(Request $request)
    {
      $request->validate([
        'email' => 'required|email:rfc,dns',
        'password' => 'required'
      ]);
      
      if(!Auth::validate($request->only(['email','password']))){
        return back()
          ->withInput($request->only($this->username()))
          ->withErrors([$this->username() => trans('auth.failed')]);
      }
      
      $user = DB::table('users')->where('email',$request->email)->first(); 

      $otp = DB::table('otps')->where('identifier', $user->email)->first();
      if($otp){
        if($otp->no_times_attempted >= config('otp-generator.allowedAttempts')){
          return back()->with([
            'message' => 'Account has been locked'
            ])
            ->withInput($request->only($this->username()));
          }
      }

      Session::put('credentials', $request->only(['email','password']));
      Session::put('user', $user);
      Session::put('identifier', $user->email);

        // send otp via email
      $this->sendOTPViaEmail($user);
      
      return redirect()->route('otp');
    }

    public function sendOTPViaEmail($user)
    {
      try{
        $otp =  Otp::generate($user->email);

        Mail::send('emails.otp', ['data'=> $otp], function($message) use ($user) {
          $message->to($user->email, 'Michael')
          ->subject('Laravel Test Mail');
          $message->from('laravel@gmail.com','Test Mail');
          });
        // return $this->getExpiry($user->email);
      }catch(\Exception $e){
        return redirect()->back()
        ->withInput(['email' => $user->email])
        ->withErrors( [$this->username() => trans('auth.contact')] );
      }
    }

    public function resendOTP(Request $request)
    {
      $user = Session::get('user');
      $this->sendOTPViaEmail($user);
      return redirect()->route('otp');
    }

    public function getExpiry(string $email)
    {
      try {
        $expiry = Otp::expiredAt($email);
        return $expiry = ($expiry->expired_at);
      }catch (\Exception $e) {
        return redirect()->back()
          ->withInput(['email' => $email])
          ->withErrors([$this->username() => trans('auth.contact')] );
      }
    }

    function hide_mail($email) {
      $stars = 4;
      $at = strpos($email,'@');
      if($at - 2 > $stars) $stars = $at - 2;
      return substr($email,0,1) . str_repeat('*',$stars) . substr($email,$at - 1);
    }

    public function otp(Request $request)
    {
      try{
        $user = Session::get('user');

        return view('auth.otp', [
          'first_name' => $user->name,
          'email' => $this->hide_mail($user->email),
          'expiry' => $this->getExpiry($user->email)
        ]);

      }catch(\Exception $e){
        return redirect()->back()
          ->withInput($request->only($this->username()))
          ->withErrors( [$this->username() => trans('auth.contact')] );          
      }
    }

    public function validateOtp(Request $request)
    {
      $request->validate([
        'otp' => 'required',
      ]);

      $identifier = Session::get('identifier');
      $expiry = $this->getExpiry($identifier);
      $credentials = Session::get('credentials');

      $verify = Otp::validate($identifier, $request->get('otp'));

        if($verify->status){
          DB::table('otps')->where('identifier', $identifier)->update([
            'expired' => 0,
            'no_times_generated' => 0,
            'no_times_attempted' => 0
          ]);

          if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->route('dashboard');
          }
        }

        return redirect()->back()
        ->with([
          'expiry' => $expiry,
          'message' => $verify->message
        ]);
    }

}
