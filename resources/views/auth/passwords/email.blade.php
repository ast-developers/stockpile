@extends('layouts.app2')

<!-- Main Content -->
@section('content')
<div class="login-box-body">
            <p class="login-box-msg">Reset Password</p>
            
            <form  method="POST" action="{{ url('/password/email') }}">
                {{ csrf_field() }}
                <div class="form-group has-feedback">
                    <input class="form-control" placeholder="Email" name="email" value="{{old('email')}}" type="email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                {{-- <div class="form-group has-feedback">
                    <select class="form-control" name="company" required>
                        <option value="">Select Your Company</option>
                    @foreach($companyData as $data)
                      <option value="{{$data->company_id}}" >{{$data->name}}</option>
                    @endforeach
                    </select>
                </div> --}}

                <div class="row">
                    <div class="col-xs-2">
                    </div><!-- /.col -->
                    <div class="col-xs-8">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Send Password Reset Link</button>
                    </div><!-- /.col -->
                    <div class="col-xs-2">
                    </div><!-- /.col -->
                </div>
            </form>

            <a href="{{url('login')}}">Log in</a><br>
            

        </div>
@endsection
