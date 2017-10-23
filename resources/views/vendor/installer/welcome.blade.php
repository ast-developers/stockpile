@extends('vendor.installer.layout')

@section('content')
	
		<div class="card-content ">
			<hr>
			<div class="center-align">
				<div class="login-logo">
				    <a href="#"><b>Stockpile</b> Application</a>
				 </div>
				<h3 class="text-center"><em>{{ trans('installer.welcome.version') }}</em></h3>
				<hr>
				<h4 class="text-center">Welcome to Stockpile Installer !</h4>
			</div>
			<p>{{ trans('installer.welcome.sub-title') }}</p>
			<ol>
				@for ($i = 1; $i < 5; $i++)
					<li>{{ trans('installer.welcome.item' . $i) }}</li>
				@endfor
			</ol>
			<p>{{ trans('installer.welcome.message') }}</p>
		</div>
		<div class="card-action">
			<a class="btn btn-success" href="{{ url('install/database') }}">
				{{ trans('installer.welcome.button') }}
				<i class="fa fa-arrow-right"></i>
			</a>
		</div>
	
@endsection