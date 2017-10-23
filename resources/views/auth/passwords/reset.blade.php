@extends('layouts.app2')

@section('content')
        <div class="login-box-body">
            <p class="login-box-msg">Reset Password</p>
            <form action='{{ url("password/resets/$userData->id") }}' method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" value="{{ $userData->id }}">
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="c_id" value="{{ $c_id }}">
            

                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password"/>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="password_confirmation" name="password_confirmation"/>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>

                <div class="row">
                    <div class="col-xs-2">
                    </div><!-- /.col -->
                    <div class="col-xs-8">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Reset Password</button>
                    </div><!-- /.col -->
                    <div class="col-xs-2">
                    </div><!-- /.col -->
                </div>
            </form>

            <a href="{{ url('/login') }}">Log in</a><br>

        </div><!-- /.login-box-body -->
@endsection
