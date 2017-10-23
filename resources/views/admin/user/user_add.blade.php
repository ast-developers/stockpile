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
              <h3 class="box-title">{{ trans('message.form.user_create_form') }}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ url('save-user') }}" method="post" id="myform1" class="form-horizontal">
            
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
              <div class="box-body">
                
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.full_name') }}</label>

                  <div class="col-sm-9">
                    <input type="text" placeholder="{{ trans('message.form.full_name') }}" class="form-control valdation_check" id="fname" name="real_name">
                    <span id="val_fname" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.email') }}</label>

                  <div class="col-sm-9">
                    <input type="text" placeholder="{{ trans('message.table.email') }}" class="form-control valdation_check" id="email" name="email">
                    <span id="val_email" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.password') }}</label>

                  <div class="col-sm-9">
                    <input type="password" placeholder="{{ trans('message.form.password') }}" class="form-control valdation_check" id="ps" name="password">
                    <span id="val_ps" style="color: red"></span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.phone') }}</label>

                  <div class="col-sm-9">
                    <input type="text" placeholder="{{ trans('message.table.phone') }}" class="form-control valdation_check" id="name" name="phone">
                    <span id="val_name" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.sidebar.role') }} </label>

                  <div class="col-sm-9">
                    <select class="form-control valdation_select select2" name="role_id" id="ss">
                    <option value="">{{ trans('message.form.select_one') }}</option>
                    @foreach ($roleData as $data)
                      <option value="{{$data->id}}" >{{$data->role}}</option>
                    @endforeach
                    </select>
                    <span id="val_ss" style="color: red"></span>
                  </div>
                </div>
            
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{{ url('users') }}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary btn-flat pull-right" type="submit">{{ trans('message.form.submit') }}</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
</section>
    @endsection

@section('js')
    <script type="text/javascript">
   // $('.select2').select2();

    $(document).ready(function(){

        $("#email").blur(function(){
            email_validate();
        });
    });
     function email_validate()
    {
        var result;
        var url = "{{url('email-valid')}}";
        var email = $("#email").val();
        var token = $("#token").val();

        if(valid_email_format(email)) {
            $('#val_email').html("");
             $.ajax({
                    url   :url,
                    async : false,
                    data:{                  // data that will be sent
                        _token:token,
                        email:email
                    },
                    type:"POST",            // type of submision
                    success:function(data){
                        console.log("sucess "+data);
                        if(data == 1) {
                            $("#val_email").html("{{ trans('message.error.email_unique_error') }}");
                            result = 0;
                        }
                        else{
                            $("#val_email").html('');
                            result = 1;
                        }
                    },
                     error: function(xhr, desc, err) {
                        
                        return 0;
                    }
                });
        } else {
            $('#val_email').html("{{ trans('message.error.required') }}");
            return 0;
        }
        return result;
    }

    function valid_email_format(email)
    {
        var re = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
        var is_email=re.test(email);
        if(!is_email) {
            return false;
        }
        return true;
    }


        $('#myform1').on('submit',function(e){
                var flag = 0;
                $('.valdation_check').each(function(){
                    var id = $(this).attr('id');

                    if ($('#'+id).val() == '')
                    {
                        $('#val_'+id).html("{{ trans('message.error.required') }}");
                        flag = 1;
                    }
                });

                $('.valdation_select').each(function(){
                    var id = $(this).attr('id');
                    if ($('#'+id).val() == '')
                    {
                        $('#val_'+id).html("{{ trans('message.error.required') }}");
                        flag = 1;
                    }
                });
                

                var em = email_validate();
                if(!em || flag == 1) {
                    e.preventDefault();
                }
        });
        $(".valdation_check").on('keypress keyup',function(){
            var nm = $(this).attr("id");
            $('#val_'+nm).html("");
        });
        $(".valdation_select").on('click',function(){
            var nm = $(this).attr("id");
            $('#val_'+nm).html("");
        });
    </script>
@endsection