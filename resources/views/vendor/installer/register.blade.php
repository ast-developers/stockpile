@extends('vendor.installer.layout')

@section('style')
	<style>
		input { margin-bottom: 2px !important };
	</style>
@endsection

@section('content')
	<div class="card">
		 <form method="post" action="{{ url('install/register') }}" role="form">	
		    <div class="card-content">
				<h3 class="text-center">{{ trans('installer.register.title') }}</h3>
				<h4 class="text-center">{{ trans('installer.register.sub-title') }}</h4>
				<hr>
				{!! csrf_field() !!}
				@foreach($fields as $key => $value)
					<div class="form-group">
						
		                  <label for="{{ $key }}">{{ trans('installer.register.base-label') . trans('installer.register-fields.' . $key)}}</label>
		                  <input class="form-control" type="{{ $value }}" id="{{ $key }}" name="{{ $key }}" value="{{ old($key) }}" >
		                
 						@if ($errors->has($key))
                            <small class="text-red ">{{ $errors->first($key) }}</small>
                       	@endif
					</div>
				@endforeach
			</div>
			<div class="card-action">
				<p><em>{{ trans('installer.register.message') }}</em></p>
				<button class="btn btn-success" type="submit">
					{{ trans('installer.register.button') }}
					<i class="fa fa-arrow-right"></i>
				</button>
			</div>
		</form>
	</div>
@endsection