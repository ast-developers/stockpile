@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
    <div class="box box-default">
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
           <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.supplier') }}</div>
          </div> 
        </div>
      </div>
    </div>

        <div class="box">
<div class="box-body">
              <h4 class="text-info text-center">{{ trans('message.table.create_suppiler') }}</h4>
                <!-- form start -->
              <form action="{{ url('save-supplier') }}" method="post" id="myform1" class="form-horizontal">
              <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
              <div class="box-body">
              <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.supp_name') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.form.full_name') }}" class="form-control valdation_check" id="fname" name="supp_name">
                    <span id="val_fname" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.email') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.form.email') }}" class="form-control valdation_check" id="lname" name="email">
                    <span id="val_lname" style="color: red"></span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.table.phone') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.table.phone') }}" class="form-control valdation_check" id="name" name="contact">
                    <span id="val_name" style="color: red"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.extra_text.street') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.extra_text.street') }}" class="form-control valdation_check" id="address" name="address">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.extra_text.city') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.extra_text.city') }}" class="form-control" id="city" name="city">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.extra_text.state') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.extra_text.state') }}" class="form-control" id="state" name="state">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.extra_text.zipcode') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.extra_text.zipcode') }}" class="form-control" id="zipcode" name="zipcode">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.extra_text.country') }}</label>
                  <div class="col-sm-6">
                        <select class="form-control select2" name="country" id="country">
                        <option value="">{{ trans('message.form.select_one') }}</option>
                        @foreach ($countries as $data)
                          <option value="{{$data->code}}" >{{$data->country}}</option>
                        @endforeach
                        </select>

                   <div style="margin-top:10px;"> 
                      <br><br>
                      <a href="{{ url('supplier') }}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                      <button class="btn btn-primary btn-flat pull-right" type="submit">{{ trans('message.form.submit') }}</button>                     
                   </div>     
                  </div>
                </div>                                                                

              </div>
              </div>
              </div>
            </form>
          </div>
        </div>
        
        <!-- /.box-footer-->
      
      <!-- /.box -->

    </section>
@endsection

@section('js')
    <script type="text/javascript">
    $(".select2").select2();
    $('#myform1').validate({
        rules: {
            supp_name: {
                required: true
            },
            email: {
                required: true
            },
            address:{
              required : true
            }, 
            city: {
                required: true
            },
            state: {
                required: true
            },
            country :{
              required : true
            }                               
        }
    });
    </script>
@endsection