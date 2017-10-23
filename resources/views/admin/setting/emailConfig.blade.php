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
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('message.table.smtp_setting') }}</h3>
            </div><br>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ url('save-email-config') }}" method="post" id="myform1" class="form-horizontal">
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.smtp_protocol') }}</label>

                  <div class="col-sm-6">
                    <label>
                      <input name="email_protocol" value="smtp" <?=isset($emailConfigData->email_protocol) && $emailConfigData->email_protocol == 'smtp' ? 'checked':""?> type="radio">{{ trans('message.table.smtp') }}&nbsp;&nbsp;
                      
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.email_encription') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{isset($emailConfigData->email_encryption) ? $emailConfigData->email_encryption : ''}}" class="form-control" name="email_encryption">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.smtp_host') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{isset($emailConfigData->smtp_host) ? $emailConfigData->smtp_host : ''}}" class="form-control" name="smtp_host">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.smtp_port') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{isset($emailConfigData->smtp_port) ? $emailConfigData->smtp_port : ''}}" class="form-control" name="smtp_port">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.smtp_email') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{isset($emailConfigData->smtp_email) ? $emailConfigData->smtp_email : ''}}" class="form-control" name="smtp_email">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.from_address') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{isset($emailConfigData->from_address) ? $emailConfigData->from_address : ''}}" class="form-control" name="from_address">
                  </div>
                </div>
              
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.from_name') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{isset($emailConfigData->from_name) ? $emailConfigData->from_name : ''}}" class="form-control" name="from_name">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.smtp_username') }}</label>

                  <div class="col-sm-6">
                    <input type="text" value="{{isset($emailConfigData->smtp_username) ? $emailConfigData->smtp_username : ''}}" class="form-control" name="smtp_username">
                  </div>
                </div> 
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.smtp_password') }}</label>

                  <div class="col-sm-6">
                    <input type="password" value="{{isset($emailConfigData->smtp_password) ? $emailConfigData->smtp_password : ''}}" class="form-control" name="smtp_password">
                  </div>
                </div>
              
              <!-- /.box-body -->
              <div class="box-footer">
                <button class="btn btn-primary btn-flat pull-right" type="submit">{{ trans('message.form.submit') }}</button>
              </div>
              <!-- /.box-footer -->
            </form>

          </div>

         <div class="box">
            <div class="box-body">
            <h4>{{ trans('message.table.test_mail') }}  <span id="msgname"></span></h4>
            <div class=""><small>{{ trans('message.table.test_message_note') }}</small></div><br>
              <form class="form-inline" id="test-email-form">
                <div class="form-group">
                  <label for="email">{{ trans('message.table.email_address') }}:</label>
                  <input type="email" class="form-control" id="email" name="email">
                </div>
                <button type="submit" class="btn btn-primary btn-flat" id="btnSubmit">{{ trans('message.table.send') }}</button>
              </form>
            </div>
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
            email_protocol: {
                required: true
            },
            email_encryption:{
               required: true,
            },
            smtp_host:{
               required: true,
            },
            smtp_port: {
                required: true
            },
            smtp_email:{
               required: true,
            },
            from_address  :{
               required: true,
            },
            from_name:{
               required: true,
            },
            smtp_username  :{
               required: true,
            },
            smtp_password:{
               required: true,
            }                     
        }
    });

    $('#test-email-form').validate({
        rules: {
            email: {
                required: true
            }                     
        }
    });

    $('#test-email-form').on('submit',function(e){
      e.preventDefault();
        var email = $('#email').val();

        if(email != '') {

        $('#btnSubmit').prop('disabled', true);

            $.ajax({
                url: '{{ URL::to('test-email')}}',
                data:{  // data that will be sent
                    email:email
                },
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                  
                    $('#msgname').html('<span style="color:green"> Email sent to <b>'+data.name+'</b> successfull !</span>');
                    $('#btnSubmit').prop('disabled', false);
                }
            });
        }

    });
     /*$("#btnSubmit").on('click', function(){
      alert("This option is not available on demo version!");
      return false;
     });*/
    
    </script>
@endsection