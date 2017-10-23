@extends('vendor.installer.layout')

@section('content')
	<div class="card">
		<div class="card-content">
			<h3 class="text-center">{{ trans('installer.end.title') }}</h3>
			<div class="card-action text-center">
				<a class="btn btn-success" href="{{ url(config('installer.login-url')) }}">
					{{ trans('installer.end.button') }}
				</a>
			</div>
		</div>
	</div>
@endsection