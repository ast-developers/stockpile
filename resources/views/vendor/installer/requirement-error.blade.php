@extends('vendor.installer.layout')

@section('content')
	<div class="card">
		<div class="card-content">
			<h3 class="text-center">{{ trans('installer.requirement-error.title') }}</h3>
			<hr>
			@if($requirementCheck == "PHP")
				<p class="text-red" >{{ trans('installer.requirement-error.php-version') }} <strong> {{ PHP_VERSION . '.'}} </strong></p>
			@else
				<p class="text-red">{{ trans('installer.requirement-error.requirement') }} <strong> {{ $requirementCheck . '.'}} </strong></p>
			@endif
			<br>
			<p class="text-red"><em>{{ trans('installer.requirement-error.message')}}<em></p>
		</div>
	</div>
@endsection