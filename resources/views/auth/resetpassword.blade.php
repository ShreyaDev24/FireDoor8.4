

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
                    <h3>Welcom back, Reset your account</h3>
                    @if (Session::has('message'))
                         <div class="alert alert-success" role="alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <div class="form-container">
                       <form class=""  method="POST" action="{{ route('reset.password.post') }}">

                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group form-fg">
                            <input type="text" id="email_address" class="input-text" name="email" placeholder="Enter email" required autofocus>
                            <i class="fa fa-envelope"></i>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="form-group form-fg">
                            <input type="password" id="password" class="input-text" name="password" required autofocus placeholder="Enter Password">
                            <i class="fa fa-unlock-alt"></i>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <div class="form-group form-fg">
                            <input type="password" id="password-confirm" class="input-text" name="password_confirmation" required autofocus placeholder="Enter Confirm Password">
                            <i class="fa fa-unlock-alt"></i>
                            @if ($errors->has('password_confirmation'))
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>

                        <div class="checkbox clearfix">
                            <a href="{{ route('login') }}" >Back to Login</a>
                        </div>
                        <div class="form-group mt-2">
                            <button type="submit" class="btn-md btn-fg btn-block">Login</button>
                        </div>

                        {{--  {{ csrf_field() }}

                            <div class="form-group form-fg">
                                <input type="password" name="password" class="input-text"  placeholder="Password">
                                <i class="fa fa-unlock-alt"></i>
                                <div class="text-danger">@if ($errors->has('password')){{ $errors->first('password') }}@endif</div>
                            </div>

                             <div class="form-group form-fg">
                                <input type="password" id="confirm_password" name="confirm_password" class="input-text"  placeholder="Password">
                                <i class="fa fa-unlock-alt"></i>
                                <div class="text-danger">@if ($errors->has('password')){{ $errors->first('password') }}@endif</div>
                            </div>
                            <div class="checkbox clearfix">
                                <a href="{{ route('login') }}" >Back to Login</a>
                            </div>
                            <div class="form-group mt-2">
                                <button type="submit" class="btn-md btn-fg btn-block">Login</button>
                            </div>  --}}
                        </form>
                    </div>
                    <p>Don't have an account? <a href="#" class="linkButton"> Contact Us</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
  </body>
</html>
