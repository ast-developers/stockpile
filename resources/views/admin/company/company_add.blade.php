@extends('layouts.app')


@section('content')

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">
          @include('layouts.includes.sub_menu')
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box">
          
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('message.form.company_create') }}</h3>
            </div>
          
            <!-- /.box-header -->
            <form action="{{ url('save-company') }}" method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.form.company') }}</label>

                  <div class="col-sm-8">
                    <input type="text" placeholder="{{ trans('message.form.company') }}" class="form-control valdation_check" id="name" name="name" value="{{ old('name') }}">
                    <span id="val_name" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.form.db_host') }}</label>

                  <div class="col-sm-8">
                      <input type="text" placeholder="{{ trans('message.form.db_host') }}" class="form-control valdation_check" id="ab" name="host" value="{{ old('host') }}">
                    <span id="val_ab" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.form.db_user') }}</label>

                  <div class="col-sm-8">
                      <input type="text" placeholder="{{ trans('message.form.db_user') }}" class="form-control valdation_check" id="bb" name="db_user" value="{{ old('db_user') }}">
                    <span id="val_bb" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.form.db_password') }}</label>

                  <div class="col-sm-8">
                      <input type="text" placeholder="{{ trans('message.form.db_password') }}" class="form-control valdation_checka" id="cc" name="db_password" value="{{ old('db_password') }}">
                    <span id="val_cc" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.form.db_name') }}</label>

                  <div class="col-sm-8">
                      <input type="text" placeholder="{{ trans('message.form.db_name') }}" class="form-control valdation_check" id="dd" name="db_name" value="{{ old('db_name') }}">
                    <span id="val_dd" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.form.full_name') }}</label>

                  <div class="col-sm-8">
                    <input type="text" placeholder="{{ trans('message.form.full_name') }}" class="form-control valdation_check" id="fname" name="real_name" value="{{ old('real_name') }}">
                    <span id="val_fname" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.form.username') }}</label>

                  <div class="col-sm-8">
                    <input type="email" placeholder="{{ trans('message.table.email') }}" class="form-control valdation_check" id="email" name="email" value="{{ old('email') }}">
                    <span id="val_email" style="color: red"></span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.form.new_company_password') }}</label>

                  <div class="col-sm-8">
                      <input type="text" placeholder="{{ trans('message.form.new_company_password') }}" class="form-control valdation_check" id="ee" name="user_pass" value="{{ old('user_pass') }}">
                    <span id="val_ee" style="color: red"></span>
                  </div>
                </div>
            
              </div>
              <!-- /.box-body -->
              
               <div class="box-footer">
                <a href="{{ url('company') }}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary pull-right btn-flat" type="submit">{{ trans('message.form.submit') }}</button>
              </div>
              <!-- /.box-footer -->
            </form>
            <!-- /.box-body -->
          </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    @include('layouts.includes.message_boxes')
@endsection

@section('js')
    <script type="text/javascript">
        $('#myform1').on('submit',function(e){
                var flag = 0;
                $('.valdation_check').each(function() {
                    var id = $(this).attr('id');
                    console.log($('#'+id).val());
                    if($('#'+id).val() == '') {

                        $('#val_'+id).html("{{ trans('message.error.required') }}");
                        flag = 1;
                    }
                });
                if(flag == 1) {
                    e.preventDefault();
                }
        });
        $(".valdation_check").on('keypress keyup',function() {
            var nm = $(this).attr("id");
            $('#val_'+nm).html("");
        });
    </script>
@endsection