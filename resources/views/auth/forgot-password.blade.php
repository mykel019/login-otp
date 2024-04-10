@extends('auth.master')
@section('content')
  <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">

    <div class="login-wrapper">

      <form id="forgot-password" action="forgot-password" method="POST" class="p-10">
        @csrf
        <h2 class="text-center">Forgot Password</h2>
        <div class="form-group">
          <input type="text" name="email" class="form-control" placeholder="Email">
          @error('email')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>

        @if (\Session::has('message'))
          <p class="text-success">{!! \Session::get('message') !!}</p>
        @endif
   
        <div class="form-group mt-0">
          <button id="reset" type="submit" class="custom-btn btn-15">Reset Password</button>
        </div>
      </form>
    </div>

    <!-- Modal -->
    {{-- <div class="modal fade" id="otp-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="resend-otp" id="forgot-password">
            @csrf
            <div class="modal-header border-b-0">
              <h5 class="modal-title" id="exampleModalLabel"></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div>
                <span>Request a new OTP?</span>
              </div>
              <div>
                <span>Are you sure you want to request a new OTP?
                  We will send the OTP to your email address, *********no@svengroup.com</span>
              </div>
            </div>
            <div class="modal-footer border-t-0">
              <button type="button" class="btn btn-default btn-cancel" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-default btn-confirm">Confirm</button>
            </div>
          </form>
        </div>
      </div>
    </div> --}}
  
  </div>
@endsection
@push('scripts')
  <script defer>
    window.onload = function() {
      let forgot_password_form = document.querySelector('#forgot-password');
      let reset_btn = document.querySelector('#reset')
      forgot_password_form.addEventListener('submit', function(){
        reset_btn.innerText = 'Loading...';
        reset_btn.setAttribute('disabled', true);
    })
    };
  </script>
@endpush

