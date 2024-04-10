@extends('auth.master')
@section('content')
  <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">

    <div class="login-wrapper">
  
      <p>To verify your identity, we’ve sent a one-time password (OTP) to your registered email address ending in {{ $email }}.</p>

      <form id="otp-form" action="validate-otp" method="POST" class="p-10">
        @csrf
        <div class="form-group">
          <label for="text">OTP</label>
          <input type="text" name="otp" class="form-control">
          @error('otp')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>

        @if (\Session::has('message'))
          <p class="error-message">{!! \Session::get('message') !!}</p>
        @endif

        <div class="wrapper">
          <p class="otp_validity"></p>
          <a href="#" class="req-otp" data-bs-toggle="modal" data-bs-target="#otp-modal">Request a new OTP</a>
        </div>

        <div class="form-group mt-2">
          <button id="otp-btn" type="submit" class="custom-btn btn-15">Verify</button>
        </div>
      </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="otp-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="resend-otp">
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
    </div>
  
  </div>
@endsection
@push('scripts')
  <script defer>
    window.onload = function() {
      let duration = 0
      let timerText = document.querySelector('.otp_validity');
        let requestNewOTP = document.querySelector('.req-otp')

      const otp_timer = setInterval(() => {
        let expiryDate = new Date(`{!! $expiry !!}`).getTime();
        let now = new Date().getTime();
    
        let distance = (expiryDate - now);
        let seconds = Math.floor((distance % 60000) / 1000).toFixed(0);
        let minutes = Math.floor(distance / 60000);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        timerText.innerText = `Wait ${minutes}:${seconds} sec to requst new OTP`;
        // Wait  to send new otp request

        if(--distance < 0){
          clearInterval(otp_timer);
          timerText.style.display = 'none';
          requestNewOTP.style.display = 'block';

          const modal = document.querySelector('#otp-modal');
          requestNewOTP.addEventListener('click', function(){
          })
        }
      }, 1000);

      const otp_form = document.querySelector('#otp-form');
      const otp_btn = document.querySelector('#otp-btn');
      otp_form.addEventListener('submit', function(event) {
        otp_btn.innerText = "Please wait...";
        otp_btn.setAttribute('disabled', true);
      })
    };
  </script>
@endpush

