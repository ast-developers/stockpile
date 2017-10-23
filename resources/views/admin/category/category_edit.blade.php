@extends('layouts.app')


@section('content')

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
        
        <div class="row">
          
          <div class="col-md-offset-3 col-md-6">
          
            <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('message.form.category_edit') }}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action='{{ url("update-category/$categoryData->category_id") }}' method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ trans('message.form.category') }}</label>

                  <div class="col-sm-10">
                    <input type="text" value="{{$categoryData->description}}" class="form-control valdation_check" id="nm" name="description">
                    <span id="val_nm" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ trans('message.form.unit') }}</label>

                  <div class="col-sm-10">
                    <select class="form-control" name="dflt_units">
                    @foreach ($unitData as $data)
                      <option value="{{$data->id}}" <?=isset($data->id) && $data->id == $categoryData->dflt_units ? 'selected':""?> >{{$data->name}}</option>
                    @endforeach
                    </select>
                  </div>
                </div>
            
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{{ url('item-category') }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-info pull-right" type="submit">{{ trans('message.form.update') }}</button>
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

        $('#myform1').on('submit',function(e){
                var flag = 0;
                $('.valdation_check').each(function() {
                    var id = $(this).attr('id');
                    console.log($('#'+id).val());
                    if ($('#'+id).val() == '') {

                        $('#val_'+id).html("{{ trans('message.error.required') }}");
                        flag = 1;
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
    
    </script>
@endsection



