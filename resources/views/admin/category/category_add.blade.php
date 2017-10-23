@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
        
        <div class="row">
          
          <div class="col-md-offset-3 col-md-6">
          
            <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('message.form.category_create') }}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ url('save-category') }}" method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label require" for="inputEmail3">{{ trans('message.form.category') }}</label>

                  <div class="col-sm-10">
                    <input type="text" placeholder="Name" class="form-control valdation_check" id="name" name="description">
                    <span id="val_name" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label require" for="inputEmail3">{{ trans('message.form.unit') }}</label>

                  <div class="col-sm-10">
                    <select class="form-control valdation_select" name="dflt_units" id="nn">
                    <option value="">{{ trans('message.form.select_one') }}</option>
                    @foreach ($unitData as $data)
                      <option value="{{$data->id}}" >{{$data->name}}</option>
                    @endforeach
                    </select>
                    <span id="val_nn" style="color: red"></span>
                  </div>
                </div>
            
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{{ url('item-category') }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-info pull-right" type="submit">{{ trans('message.form.submit') }}</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          </div>
          
        </div>
        
        <!-- /.box-footer-->
      
      <!-- /.box -->

    </section>
@endsection

@section('js')
    <script type="text/javascript">
        $('#myform1').on('submit',function(e) {
                var flag = 0;
                $('.valdation_check').each(function() {
                    var id = $(this).attr('id');
                    console.log($('#'+id).val());
                    if($('#'+id).val() == '')
                    {
                        $('#val_'+id).html("{{ trans('message.error.required') }}");
                        flag = 1;
                    }
                });
                $('.valdation_select').each(function() {
                    var id = $(this).attr('id');
                    //console.log($('#'+id).val());
                    if ($('#'+id).val() == '') {
                    
                        $('#val_'+id).html("{{ trans('message.error.required') }}");
                        flag = 1;
                        //console.log('country '+flag);
                    }
                });
                if (flag == 1) {
                    e.preventDefault();
                }
        });
        $(".valdation_check").on('keypress keyup',function() {
            var nm = $(this).attr("id");
            $('#val_'+nm).html("");
        });
        $(".valdation_select").on('click',function() {
            var nm = $(this).attr("id");
            $('#val_'+nm).html("");
        });
    </script>
@endsection



