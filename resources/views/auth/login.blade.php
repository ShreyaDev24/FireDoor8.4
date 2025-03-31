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
    <div class="container">
        <div class="row">
            <div class="login">
                <div class="login-section">
                    <div class="logo clearfix" style="padding-bottom:10px">
                    <div class="login-logo">
                    <img src="{{url('/images/logo/jfdswhitelogo.png')}}" height="100px" alt="comany logo">
                    </div>


                    </div>
                    <h3>Welcome back, Please login to your account</h3>
                    @if($errors->has('UserEmail'))
                        <p style="color:red; margin-left: 1px; font-weight: bold;">
                        {{$errors->first('UserEmail')}}</p>
                    @endif
                    @if($errors->has('password'))
                        <p style="color:red; margin-left: 1px; font-weight: bold;">
                        {{$errors->first('password')}}</p>
                    @endif
                    @if (Session::has('message'))
                         <div class="alert alert-success" role="alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <div class="form-container">
                       <form class=""  method="POST" action="{{ route('custom-login') }}">
                        {{ csrf_field() }}
                            <div class="form-group form-fg">
                                <input type="email" name="UserEmail" id="exampleInputEmail1" class="input-text" value="{{ old('UserEmail') }}" placeholder="Email Address">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="form-group form-fg">
                                <input type="password" name="password" class="input-text"  placeholder="Password">
                                <i class="fa fa-unlock-alt"></i>
                                {{--  <div class="text-danger">@if ($errors->has('password')){{ $errors->first('password') }}@endif</div>  --}}
                            </div>
                            <div class="checkbox clearfix">
                                <div class="form-check checkbox-fg">
                                    <input class="form-check-input" type="checkbox" id="customControlAutosizing" name="remember" {{ old('remember') ? 'checked' : '' }} id="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="{{ route('ForgotPassword') }}" >Forgot Password</a>
                            </div>
                            <div class="form-group mt-2">
                                <button type="submit" class="btn-md btn-fg btn-block">Login</button>
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
