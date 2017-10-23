@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
        <div class="row">
          <div class="col-md-offset-2 col-md-8">
            <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('message.form.change_password_form') }}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action='{{ url("change-password/$userData->id") }}' method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
              <div class="box-body">
                
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.old_password') }}</label>

                  <div class="col-sm-7">
                    <input type="password" class="form-control valdation_check"  name="old_pass" id="name">
                    <span id="val_name" style="color: red"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.new_password') }}</label>

                  <div class="col-sm-7">
                    <input type="password" class="form-control valdation_check" id="n_pass" name="new_pass">
                    <span id="val_n_pass" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.re_password') }}</label>

                  <div class="col-sm-7">
                    <input type="password" class="form-control valdation_check" id="r_pass" >
                    <span id="val_r_pass" style="color: red"></span>
                  </div>
                </div>
                
            
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button class="btn btn-primary btn-flat pull-right" type="submit">{{ trans('message.form.submit') }}</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          </div>
        </div>
    </section>
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

                var n = $('#n_pass').val();
                var r = $('#r_pass').val();
                if(n != r) {
                  
                  $('#val_r_pass').html("{{ trans('message.error.password_donot_match') }}");
                  e.preventDefault();
                }
        });
        $(".valdation_check").on('keypress keyup',function() {
            var nm = $(this).attr("id");
            $('#val_'+nm).html("");
        });
    </script>
@endsection