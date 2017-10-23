@extends('layouts.customer_panel')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            <div class="col-md-9">
            <h4>{{ trans('message.customer_panel.your_profile')}}</h4>
              <form action='{{ url("customer/profile") }}' method="post" class="form-horizontal" enctype="multipart/form-data" id="myform1">
                  {!! csrf_field() !!}
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="inputName">{{ trans('message.form.full_name') }}</label>

                    <div class="col-sm-10">
                      <input type="text" value="{{$userData->name}}" class="form-control valdation_check" id="fname" name="name">
                    <span id="val_fname" style="color: red"></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="inputEmail">Email</label>

                    <div class="col-sm-10">
                      <input type="email" value="{{$userData->email}}" class="form-control valdation_check" id="em" name="email" readonly>
                    <span id="val_em" style="color: red"></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="inputName">{{ trans('message.table.phone') }}</label>

                    <div class="col-sm-10">
                      <input type="text" value="{{$userData->phone}}" class="form-control valdation_check" id="phn" name="phone">
                    <span id="val_phn" style="color: red"></span>
                    </div>
                  </div>

                <h4>{{ trans('message.customer_panel.change_password')}} <small>{{ trans('message.customer_panel.password_mgs')}}</small></h4>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="inputName">{{ trans('message.customer_panel.password')}}</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="password">
                       <span id="password" style="color: red"></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="inputName">{{ trans('message.customer_panel.password_confirm')}}</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="password_confirmation" id="con_password">
                       <span id="confirm_password" style="color: red"></span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button class="btn btn-primary" type="submit">{{ trans('message.form.update') }}</button>
                    </div>
                  </div>
                </form>
            </div>
            </div>
            <!-- /.box-body -->
          </div>
        <!-- /.box-footer-->
    </section>
@endsection
@section('js')
    <script type="text/javascript">

    </script>
@endsection