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
              <h3 class="box-title">{{ trans('message.form.user_edit_form') }}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action='{{ url("update-user/$userData->id") }}' method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
              <div class="box-body">
                
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ trans('message.form.full_name') }}</label>

                  <div class="col-sm-10">
                    <input type="text" value="{{$userData->real_name}}" class="form-control valdation_check" id="fname" name="real_name">
                    <span id="val_fname" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ trans('message.table.email') }}</label>

                  <div class="col-sm-10">
                    <input type="email" value="{{$userData->email}}" class="form-control valdation_check" id="em" name="email" readonly>
                    <span id="val_em" style="color: red"></span>
                  </div>
                </div>
                
                
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ trans('message.table.phone') }}</label>

                  <div class="col-sm-10">
                    <input type="text" value="{{$userData->phone}}" class="form-control valdation_check" id="name" name="phone">
                    <span id="val_name" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ trans('message.sidebar.role') }}</label>

                  <div class="col-sm-10">
                    <select class="form-control" name="role_id" required>
                    <option value="">----Select One----</option>
                    @foreach ($roleData as $data)
                      <option value="{{$data->id}}" <?=isset($data->id) && $data->id == $userData->role_id ? 'selected':""?> >{{$data->role}}</option>
                    @endforeach
                    </select>
                  </div>
                </div>
            
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{{ url('users') }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-info pull-right" type="submit">{{ trans('message.form.submit') }}</button>
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
        $('#myform1').on('submit',function(e){
                var flag = 0;
                $('.valdation_check').each(function(){
                    var id = $(this).attr('id');
                    console.log($('#'+id).val());
                    if ($('#'+id).val() == '')
                    {
                        $('#val_'+id).html("{{ trans('message.error.required') }}");
                        flag = 1;
                    }
                });
                if (flag == 1) {
                    e.preventDefault();
                }
        });
        $(".valdation_check").on('keypress keyup',function(){
            var nm = $(this).attr("id");
            $('#val_'+nm).html("");
        });
    </script>
@endsection