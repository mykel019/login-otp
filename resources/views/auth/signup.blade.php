@extends('auth.master')
@section('content')
  <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">

    <div class="login-wrapper">
      <h1>Register</h1>
      @if (\Session::has('message'))
        <div class="alert alert-success text-center" role="alert">
          {!! Session::get('message') !!}
        </div>
      @endif
      @if (\Session::has('error'))
        <div class="alert alert-danger text-center" role="alert">
          {!! \Session::get('error') !!}
        </div>
      @endif
      <form id="register-form" action="register" method="POST" class="p-10">
        @csrf
        <div class="form-group">
          <label for="email">Email</label>
          <div class="flex items-center gap-x-1">
            <input type="email" name="email" class="form-control email" value="{{ old('email') }}">
          </div>
          @error('email')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="name">Name</label>
          <div class="flex items-center gap-x-1">
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
          </div>
          @error('name')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="flex items-center gap-x-1">
            <input type="password" name="password" class="form-control">
          </div>
          @error('password')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="password">Confirm Password</label>
          <div class="flex items-center gap-x-1">
            <input type="password" name="password_confirmation" class="form-control">
          </div>
        </div>
        <div class="form-group mt-2 text-center">
          <button id="register-btn" type="submit" class="custom-btn btn-15">REGISTER</button>
          <p class="mt-3"><span>Already have an account? </span>
            <a href="/login" type="submit" class="no-underline">Login</a>
          </p>
        </div>      
      </form>
    </div>
  
  </div>
@endsection
@push('scripts')
  <script defer>
    const register_form = document.querySelector('#register-form');
    const register_btn  = document.querySelector('#register-btn');
    register_form.addEventListener('submit', function(){
      register_btn.innerText = 'Loading...';
      register_btn.setAttribute('disabled', true);
    })
  </script>
@endpush
