@extends('layouts.app')
@section('content')

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">
          @include('layouts.includes.finance_menu')
        </div>
        <!-- /.col -->
        <div class="col-md-9">

          <div class="box box-default">
            <div class="box-body">
              <div class="row">
                <div class="col-md-9">
                 <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.sales_types') }}</div>
                </div> 
                <div class="col-md-3">
                  @if(!empty(Session::get('salestype_add')))
                    <a href="#" data-toggle="modal" data-target="#add-saleType" class="btn btn-block btn-default btn-flat btn-border-orange"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.table.add_new') }}</a>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>{{ trans('message.form.name') }}</th>
                  <th>{{ trans('message.table.tax_included') }}</th>
                   <th>{{ trans('message.table.default') }}</th>
                  <th width="5%">{{ trans('message.table.action') }}</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                  $type = [1,2];
                ?>
                @foreach ($salesTypeData as $data)
                <tr>
                  <td>{{ $data->sales_type }}</td>
                  <td>{{ $data->tax_included == 1 ? 'Yes':'No' }}</td>
                  <td>{{ $data->defaults == 1 ? 'Yes' : 'No' }}</td>
                  <td>
              @if(! in_array($data->id, $type))
                  @if(!empty(Session::get('salestype_edit')))
                  <a title="{{trans('message.form.edit')}}" class="btn btn-xs btn-primary edit_saletype" id="{{$data->id}}" href="javascript:void(0)"><span class="fa fa-edit"></span></a> &nbsp;
                  @endif

                  @if(!empty(Session::get('salestype_delete')))
                  <form method="POST" action="{{ url("delete-sales-type/$data->id") }}" accept-charset="UTF-8" style="display:inline">
                      {!! csrf_field() !!}
                      <button title="{{ trans('message.form.Delete') }}" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_sales_type_header') }}" data-message="{{ trans('message.table.delete_sales_type') }}">
                          <i class="glyphicon glyphicon-trash"></i> 
                      </button>
                  </form>
                  @endif
              @endif
                  </td>
                </tr>
               @endforeach
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

<div id="add-saleType" class="modal fade" role="dialog" style="display: none;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">{{ trans('message.table.add_new') }}</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" action='{{url("save-sales-type")}}' method="post" id="addTex">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token-rm">
          
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.name') }}</label>

            <div class="col-sm-6">
              <input type="text" class="form-control" name="sales_type">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.tax_included') }}</label>

            <div class="col-sm-6">
              <select class="form-control" name="tax_included" id="nn">
                      
                <option value="1" >Yes</option>
                <option value="0" >No</option>
              
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.default') }}</label>

            <div class="col-sm-6">
                <select class="form-control" name="defaults" >
                    <option value="0" >No</option>
                    <option value="1" >Yes</option>
                </select>
            </div>
          </div>

          
          <div class="form-group">
            <label for="btn_save" class="col-sm-3 control-label"></label>
            <div class="col-sm-6">
              <button type="button" class="btn btn-info btn-flat" data-dismiss="modal">{{ trans('message.form.close') }}</button>
              <button type="submit" class="btn btn-primary btn-flat">{{ trans('message.form.submit') }}</button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>


<div id="edit-saleType" class="modal fade" role="dialog" style="display: none;">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">{{ trans('message.table.add_new') }}</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" action='{{url("update-sales-type")}}' method="post" id="editsaletype">
        <input type="hidden" value="{{csrf_token()}}" name="_token" >
          
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.name') }}</label>

            <div class="col-sm-6">
              <input type="text" class="form-control" name="sales_type" id="sales_type">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.tax_included') }}</label>

            <div class="col-sm-6">
              <select class="form-control" name="tax_included" id="tax_included">
                      
                <option value="1" >Yes</option>
                <option value="0" >No</option>
              
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.default') }}</label>

            <div class="col-sm-6">
                <select class="form-control" name="defaults" id="defaults">
                    <option value="0" >No</option>
                    <option value="1" >Yes</option>
                </select>
            </div>
          </div>
          <input type="hidden" name="type_id" id="type_id">

          @if(!empty(Session::get('salestype_edit')))
          <div class="form-group">
            <label for="btn_save" class="col-sm-3 control-label"></label>
            <div class="col-sm-6">
              <button type="button" class="btn btn-info btn-flat" data-dismiss="modal">{{ trans('message.form.close') }}</button>
              <button type="submit" class="btn btn-primary btn-flat">{{ trans('message.form.update') }}</button>
            </div>
          </div>
          @endif
        </form>
      </div>
    </div>

  </div>
</div>

    </section>
    @include('layouts.includes.message_boxes')
@endsection

@section('js')
    <script type="text/javascript">
      $(function () {
        $("#example1").DataTable({
          "columnDefs": [ {
            "targets": 3,
            "orderable": false
            } ],
            
            "language": '{{Session::get('dflt_lang')}}',
            "pageLength": '{{Session::get('row_per_page')}}'
        });
        
      });

      $('.edit_saletype').on('click', function() {
        var id = $(this).attr("id");

        $.ajax({
            url: '{{ URL::to('edit-sales-type')}}',
            data:{  // data that will be sent
                id:id
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
              
                $('#sales_type').val(data.sales_type);
                $('#tax_included').val(data.tax_included);
                $('#type_id').val(data.id);
                $('#defaults').val(data.defaults);

                $('#edit-saleType').modal();
            }
        });

    });

      $('#addTex').validate({
        rules: {
            sales_type: {
                required: true
            },
            tax_included: {
                required: true
            }                     
        }
    });

      $('#editsaletype').validate({
        rules: {
            sales_type: {
                required: true
            },
            tax_included: {
                required: true
            }                     
        }
    });

    </script>
@endsection