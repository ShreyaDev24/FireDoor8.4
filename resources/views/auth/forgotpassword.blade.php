<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App</title>
      <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/login_page.css">
  </head>
  <body>
   <div class="login-fg">
    <div class="container-fluid">
        <div class="row">
            <div class="login">
                <div class="login-section">
                    <div class="logo clearfix" style="padding-bottom:10px">
                    <div class="login-logo">
                    <img src="{{url('/images/logo/jfdswhitelogo.png')}}" height="100px" alt="comany logo">
                    </div>
                    </div>
                    @if (Session::has('message'))
                         <div class="alert alert-success" role="alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <h3>Welcom back, Please enter your email for otp</h3>
                    <div class="form-container">
                       <form class="" id="signupForm"  method="POST" action="{{ route('OTP') }}">
                        {{ csrf_field() }}
                            <div class="form-group form-fg">
                                <input type="email" required name="UserEmail" id="exampleInputEmail1" class="input-text" value="{{ old('UserEmail') }}" placeholder="Email Address">
                                <i class="fa fa-envelope"></i>
                                @if(session()->has('error'))
                                <p style="color:red; text-align: left; font-weight: bold; margin-left: 10px">Email does not exists!</p>
                                @endif
                            </div>
                            <div class="form-group mt-2">
                                <button type="submit" class="btn-md btn-fg btn-block">Submit</button>
                            </div>
                        </form>
                    </div>
                    <p>Don't have an account? <a href="mailto:info@jfds.co.uk" class="linkButton"> Contact Us</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
  </body>
</html>
