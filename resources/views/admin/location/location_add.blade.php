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
              <h3 class="box-title">{{ trans('message.form.location_create') }}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ url('save-location') }}" method="post" id="myform1" class="form-horizontal">
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.location_name') }}</label>

                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.form.location_name') }}" class="form-control valdation_check" id="name" name="location_name">
                    <span id="val_name" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.location_code') }}</label>

                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.form.location_code') }}" class="form-control valdation_check" id="loc_code" name="loc_code">
                    <span id="val_loc_code" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.delivery_address') }}</label>

                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.form.delivery_address') }}" id="address" class="form-control valdation_check" name="delivery_address">
                    <span id="val_address" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.phone_one') }}</label>

                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.form.phone_one') }}" class="form-control" name="phone">
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.fax') }}</label>

                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.form.fax') }}" class="form-control" name="fax">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.email') }}</label>

                  <div class="col-sm-6">
                    <input type="email" placeholder="{{ trans('message.form.email') }}" class="form-control" name="email">
                  </div>
                </div>
              
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.contact') }}</label>

                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.form.contact') }}" class="form-control" name="contact">
                  </div>
                </div>
              
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{{ url('location') }}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary pull-right btn-flat" type="submit">{{ trans('message.form.submit') }}</button>
              </div>
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
            loc_code:{
               required: true,
               remote: "{{url('loc_code-valid')}}"
            },
            delivery_address: {
                required: true
            }                     
        }
    });
    </script>
@endsection