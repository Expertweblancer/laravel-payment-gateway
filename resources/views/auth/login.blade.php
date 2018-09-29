@extends('layouts.app')

@section('content')

    <section class="auth-form">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">@lang('app.login_desc')</div>
                        <div class="panel-body">

                            @include('admin.flash_msg')


                            @if(get_option('enable_social_login') == 1)
                                <div class="row row-sm-offset-3">

                                    @if(get_option('enable_facebook_login') == 1)
                                        <div class="col-xs-6">
                                            <a href="{{ route('facebook_redirect') }}" class="btn btn-lg btn-block btn-facebook">
                                                <i class="fa fa-facebook visible-xs"></i>
                                                <span class="hidden-xs"><i class="fa fa-facebook-square"></i> Facebook</span>
                                            </a>
                                        </div>
                                    @endif

                                    @if(get_option('enable_google_login') == 1)
                                        <div class="col-xs-6">
                                            <a href="{{ route('google_redirect') }}" class="btn btn-lg btn-block btn-google">
                                                <i class="fa fa-google-plus visible-xs"></i>
                                                <span class="hidden-xs"><i class="fa fa-google-plus-square"></i> Google+</span>
                                            </a>
                                        </div>
                                    @endif

                                </div>
                                <hr />
                            @endif

                            <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="col-md-4 control-label">Password</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password" required>

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Login
                                        </button>

                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            Forgot Your Password?
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
