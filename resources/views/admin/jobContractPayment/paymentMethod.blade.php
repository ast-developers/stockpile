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
                 <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.payment_methods') }}</div>
                </div> 
                <div class="col-md-3">
                   @if(!empty(Session::get('paymentmethod_add')))
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#add-unit" class="btn btn-block btn-default btn-flat btn-border-orange"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.table.add_new') }}</a>
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
                  <th>{{ trans('message.invoice.name') }}</th>
                  <th>{{ trans('message.table.default') }}</th>
                  <th width="5%">{{ trans('message.table.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($paymentMethodData as $data)
                <tr>
                  <td>{{ $data->name }}</td>
                  <td>{{ $data->defaults == 1 ? 'Yes' : 'No' }}</td>
                  <td>
              
              @if (!empty(Session::get('paymentmethod_edit')))
                      <a title="{{ trans('message.form.edit') }}" href="javascript:void(0)"  class="btn btn-xs btn-primary edit_unit" id="{{$data->id}}" ><span class="fa fa-edit"></span></a> &nbsp;
              @endif
              
              @if (!empty(Session::get('paymentmethod_delete')))  
               @if(!in_array($data->id,[1,2]))  
                      <form method="POST" action="{{ url("payment/method/delete/$data->id") }}" accept-charset="UTF-8" style="display:inline">
                          {!! csrf_field() !!}
                          <button title="{{ trans('message.form.Delete') }}" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_method_header') }}" data-message="{{ trans('message.table.delete_method') }}">
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
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>

<div id="add-unit" class="modal fade" role="dialog" style="display: none;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">{{ trans('message.table.add_new') }}</h4>
      </div>
      <div class="modal-body">
        <form action="{{url('payment/method/add')}}" method="post" id="addUnit" class="form-horizontal">
            {!! csrf_field() !!}
          
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.name') }}</label>

            <div class="col-sm-6">
              <input type="text" placeholder="{{ trans('message.table.name') }}" class="form-control" name="name">
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

<div id="edit-unit" class="modal fade" role="dialog" style="display: none;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">{{ trans('message.table.add_new') }}</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('payment/method/update') }}" method="post" id="editUnit" class="form-horizontal">
            {!! csrf_field() !!}
          
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.name') }}</label>

            <div class="col-sm-6">
              <input type="text" placeholder="Name" class="form-control" name="name" id="name">
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
          <input type="hidden" name="id" id="m_id">

          
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
    @include('layouts.includes.message_boxes')
@endsection

@section('js')
    <script type="text/javascript">
      $(function () {
        $("#example1").DataTable({
          "columnDefs": [ {
            "targets": 2,
            "orderable": false
            } ],
            
            "language": '{{Session::get('language')}}'
        });
        
      });

       $('#addUnit').validate({
            rules: {
                name: {
                    required: true
                }               
            }
        });

       $('.edit_unit').on('click', function() {
        var id = $(this).attr("id");

        $.ajax({
            url: '{{ URL::to('payment/method/edit')}}',
            data:{  // data that will be sent
                id:id
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
              
                $('#name').val(data.name);
                $('#defaults').val(data.defaults);
                $('#m_id').val(data.id);

                $('#edit-unit').modal();
            }
        });

        $('#editUnit').validate({
            rules: {
                name: {
                    required: true
                }                    
            }
        });

    });
    </script>
@endsection