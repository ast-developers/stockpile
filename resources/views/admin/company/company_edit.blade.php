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
            <form action='{{ url("update-company/$companyData->company_id") }}' method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="inputEmail3">{{ trans('message.form.company') }}</label>

                  <div class="col-sm-8">
                    <input type="text" placeholder="{{ trans('message.form.company') }}" class="form-control valdation_check" id="name" name="name" value="{{ $companyData->name }}">
                    <span id="val_name" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="inputEmail3">{{ trans('message.form.db_host') }}</label>

                  <div class="col-sm-8">
                      <input type="text" placeholder="{{ trans('message.form.db_host') }}" class="form-control valdation_check" id="ab" name="host" value="{{ $companyData->host }}" readonly>
                    <span id="val_ab" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="inputEmail3">{{ trans('message.form.db_user') }}</label>

                  <div class="col-sm-8">
                      <input type="text" placeholder="{{ trans('message.form.db_user') }}" class="form-control valdation_check" id="bb" name="db_user" value="{{ $companyData->db_user }}" readonly>
                    <span id="val_bb" style="color: red"></span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="inputEmail3">{{ trans('message.form.db_name') }}</label>

                  <div class="col-sm-8">
                      <input type="text" placeholder="{{ trans('message.form.db_name') }}" class="form-control valdation_check" id="dd" name="db_name" value="{{ $companyData->db_name }}" readonly>
                    <span id="val_dd" style="color: red"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label" for="inputEmail3">{{ trans('message.table.default') }}</label>

                  <div class="col-sm-8">
                    <select class="form-control valdation_select" name="default" id="nn">
                      
                      <option value="Yes" <?=isset($companyData->default) && $companyData->default == 'Yes' ? 'selected':""?> >Yes</option>
                      <option value="No"  <?=isset($companyData->default) && $companyData->default == 'No' ? 'selected':""?> >No</option>
                    
                    </select>
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