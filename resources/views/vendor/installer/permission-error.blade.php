@extends('vendor.installer.layout')

@section('content')
	<div class="card">
		<div class="card-content white-text">
			<h3 class="text-center">{{ trans('installer.permission-error.title') }}</h3>
			<hr>
			<h4 class="text-center">{{ trans('installer.permission-error.sub-title') }} <strong> {{ $permissionCheck . '.'}} </strong></h4>
			<br>
			<p class="text-red"><em>{{ trans('installer.permission-error.message')}}<em></p>
		</div>
	</div>
@endsection