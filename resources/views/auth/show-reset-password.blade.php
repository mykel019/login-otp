@extends('auth.master')
@section('content')
  <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">

    <div class="login-wrapper">
      <h2 class="text-center">Reset Password</h2>

      @if (\Session::has('message'))
        <p class="error-message">{!! \Session::get('message') !!}</p>
      @endif
      <form id="login-form" action="{{ route('updatepassword') }}" method="POST" class="p-10">
        @csrf
        <div class="form-group">
          <label for="password">Password</label>
          <div class="flex">
            <input type="password" name="password" class="form-control">
          </div>
          @error('password')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-group">
          <label for="password">Password Confirmation</label>
          <input type="password" name="password_confirmation" class="form-control">
          @error('password_confirmation')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-group mt-2 text-center">
          <button id="login-btn" type="submit" class="custom-btn btn-15">SUBMIT</button>
        </div>
      </form>
    </div>
  
  </div>
@endsection
@push('scripts')
  <script defer>
    const login_form = document.querySelector('#login-form');
    const login_btn  = document.querySelector('#login-btn');
    login_form.addEventListener('submit', function(){
      login_btn.innerText = 'Loading...';
      login_btn.setAttribute('disabled', true);
    })
  </script>
@endpush
