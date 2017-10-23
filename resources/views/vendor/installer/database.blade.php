@extends('vendor.installer.layout')

@section('style')
	<style>
		.card-panel { display: none; }
	</style>
@endsection

@section('content')
	<div class="card">
		<h3 class="text-center">{{ trans('installer.database.title') }}</h3>
		<h4 class="text-center">{{ trans('installer.database.sub-title') }}</h4>
		<hr>
		 <form method="post" action="{{ url('install/database') }}" role="form">
		 {!! csrf_field() !!}
              <div class="box-body">
                <!--<div class="form-group">
                  <label for="exampleInputEmail1">Company name</label>
                  <input class="form-control" type="text" name="company" placeholder="Enter Company Name" required>
                </div>-->
                <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('installer.database.dbname-label') }}</label>
                  <input class="form-control" type="text" id="dbname" name="dbname" value="{{ $database }}" placeholder="Enter Database Name" required>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('installer.database.username-label') }}</label>
                  <input class="form-control" type="text" id="username" name="username" value="{{ $username }}" placeholder="Enter User Name" required>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">{{ trans('installer.database.password-label') }}</label>
                  <input class="form-control" type="text" id="password" name="password" value="{{ $password }}" placeholder="Password">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('installer.database.host-label') }}</label>
                  <input class="form-control" type="text" id="host" name="host" value="{{ $host }}" placeholder="Enter Host Name" required>
                </div>
                
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-success">Submit <i class="fa fa-arrow-right"></i></button>
              </div>
        </form>				
	</div>
		
	<div class="card-panel">
		<h4 class="text-center"><b>{{ trans('installer.database.wait') }}</b></h4>

		<div class="loader"></div>
	</div>	
@endsection

@section('script')
	<script>
		$(function(){
			$(document).on('submit', 'form', function(e) {  
				$('.card').hide();
				$('.card-panel').show();
			});
		})		
	</script>
@endsection