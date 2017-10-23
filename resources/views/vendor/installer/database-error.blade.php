@extends('vendor.installer.layout')

@section('content')
	<div class="card">
		<div class="card-content">
			<h3 class="text-center">{{ trans('installer.database-error.title') }}</h3>
			<hr>
			<p class="text-green">{{ trans('installer.database-error.sub-title') }}</p>
			<ol>
				@for ($i = 1; $i < 4; $i++)
					<li class="text-red">{{ trans('installer.database-error.item' . $i) }}</li>
				@endfor
			</ol>
			<p>{{ trans('installer.database-error.message') }}</p>
		</div>
		<div class="card-action">
			<a class="btn btn-success" href="{{ url('install/database') }}">
				{{ trans('installer.database-error.button') }}
			</a>
		</div>
	</div>
@endsection