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
                 <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.taxes') }}</div>
                </div> 
                <div class="col-md-3">
                  @if(!empty(Session::get('tax_add')))
                    <a href="#" data-toggle="modal" data-target="#add-tax" class="btn btn-block btn-default btn-flat btn-border-orange"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.table.add_new') }}</a>
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
                  <th>{{ trans('message.table.tax_rate') }} (%)</th>
                  <th>{{ trans('message.table.default') }}</th>
                  <th width="5%">{{ trans('message.table.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($taxData as $data)
                <tr>
                  <td><a href="javascript:void(0)" class="edit_tax" id="{{$data->id}}">{{ $data->name }}</a></td>
                  <td>{{ $data->tax_rate }}</td>
                  <td>{{ $data->defaults == 1 ? 'Yes' : 'No' }}</td>
                  <td>
                      @if(!empty(Session::get('tax_edit')))
                      <a title="{{trans('message.form.edit')}}" class="btn btn-xs btn-primary edit_tax" id="{{$data->id}}"><span class="fa fa-edit"></span></a> &nbsp;
                      @endif
                      @if(!empty(Session::get('tax_delete')))
                      @if(!in_array($data->id,[1,2,3]))
                      <form method="POST" action="{{ url("delete-tax/$data->id") }}" accept-charset="UTF-8" style="display:inline">
                          {!! csrf_field() !!}
                          <button title="{{ trans('message.form.Delete') }}" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_unit_header') }}" data-message="{{ trans('message.table.delete_unit') }}">
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

<div id="add-tax" class="modal fade" role="dialog" style="display: none;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">{{ trans('message.table.add_new') }}</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" action='{{url("save-tax")}}' method="post" id="addTex">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token-rm">
          
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.name') }}</label>

            <div class="col-sm-6">
              <input type="text" class="form-control" name="name">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.tax_rate') }} (%)</label>

            <div class="col-sm-6">
              <input type="number" class="form-control" name="tax_rate">
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


<div id="edit-tax" class="modal fade" role="dialog" style="display: none;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">{{ trans('message.table.update_tax') }}</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" action='{{url("update-tax")}}' method="post" id="editTex">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token-rm">
          
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.name') }}</label>

            <div class="col-sm-6">
              <input type="text" placeholder="Name" class="form-control" name="name" id="tax_nm">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.tax_rate') }} (%)</label>

            <div class="col-sm-6">
              <input type="number" class="form-control" name="tax_rate" id="tax_rate">
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
          <input type="hidden" name="tax_id" id="tax_id">

          @if(!empty(Session::get('tax_edit')))
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

      $('.edit_tax').on('click', function() {
        var id = $(this).attr("id");

        $.ajax({
            url: '{{ URL::to('edit-tax')}}',
            data:{                  // data that will be sent
                id:id
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
              
                $('#tax_nm').val(data.name);
                $('#tax_rate').val(data.tax_rate);
                $('#tax_id').val(data.id);
                $('#defaults').val(data.defaults);

                $('#edit-tax').modal();
            }
        });

    });

      $('#addTex').validate({
        rules: {
            name: {
                required: true
            },
            tax_rate: {
                required: true
            }                     
        }
    });

      $('#editTex').validate({
        rules: {
            name: {
                required: true
            },
            tax_rate: {
                required: true
            }                     
        }
    });

    </script>
@endsection