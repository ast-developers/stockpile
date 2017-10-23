@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          @include('layouts.includes.company_menu')
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('message.form.location_edit') }}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action='{{ url("update-location/$locationData->id") }}' method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}

                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.location_name') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{$locationData->location_name}}" class="form-control valdation_check" id="nm" name="location_name">
                    <span id="val_nm" style="color: red"></span>
                  </div>
                </div>
              
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.location_code') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{$locationData->loc_code}}" class="form-control valdation_check" id="loc" name="loc_code" readonly>
                    <span id="val_loc" style="color: red"></span>
                  </div>
                </div>
              
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.delivery_address') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{$locationData->delivery_address}}" class="form-control valdation_check" id="ad" name="delivery_address">
                    <span id="val_ad" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.default_loc') }}</label>

                  <div class="col-sm-6">
                    <select class="form-control valdation_select" name="default" id="nn">
                      
                      <option value="1" <?=isset($locationData->inactive) && $locationData->inactive == 1 ? 'selected':""?> >Yes</option>
                      <option value="0"  <?=isset($locationData->inactive) && $locationData->inactive == 0 ? 'selected':""?> >No</option>
                    
                    </select>
                  </div>
                </div>
              
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.phone_one') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{$locationData->phone}}" class="form-control" name="phone">
                  </div>
                </div>
              
                
              
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.fax') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{$locationData->fax}}" class="form-control" name="fax">
                  </div>
                </div>
              
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.email') }}</label>

                  <div class="col-sm-6">
                    <input type="email" value="{{$locationData->email}}" class="form-control" name="email">
                  </div>
                </div>
              
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.contact') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{$locationData->contact}}" class="form-control" name="contact">
                  </div>
                </div>
              
              <!-- /.box-body -->
              @if (!empty(Session::get('loc_edit')))
              <div class="box-footer">
                <a href="{{ url('location') }}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary pull-right btn-flat" type="submit">{{ trans('message.form.update') }}</button>
              </div>
              @endif
              <!-- /.box-footer -->
            </form>
          </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    @include('layouts.includes.message_boxes')
@endsection

@section('js')
    <script type="text/javascript">
      $('#myform1').validate({
        rules: {
            location_name: {
                required: true
            },
            delivery_address: {
                required: true
            }  
        }
    });
    </script>
@endsection