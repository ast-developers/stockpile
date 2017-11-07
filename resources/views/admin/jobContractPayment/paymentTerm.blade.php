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
                 <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.payment_terms') }}</div>
                </div> 
                <div class="col-md-3">
                   @if(!empty(Session::get('paymentterm_add')))
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
                  <th>{{ trans('message.form.terms') }}</th>
                  <th>{{ trans('message.invoice.due_day') }}</th>
                  <th>{{ trans('message.table.default') }}</th>
                  <th width="5%">{{ trans('message.table.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($paymentTermData as $data)
                <tr>
                  <td>{{ $data->terms }}</td>
                  <td>{{ $data->days_before_due }}</td>
                  <td>{{ $data->defaults == 1 ? 'Yes' : 'No' }}</td>
                  <td width="10%">
                    @if(!empty(Session::get('paymentterm_edit')))
                      <a title="{{ trans('message.form.Edit') }}" href="javascript:void(0)"  class="btn btn-xs btn-primary edit_unit" id="{{$data->id}}" ><span class="fa fa-edit"></span></a> &nbsp;
                    @endif

                    @if(!empty(Session::get('paymentterm_delete')))
                    @if(!in_array($data->id,[1,2,3]))
                      <form method="POST" action="{{ url("payment/terms/delete/$data->id") }}" accept-charset="UTF-8" style="display:inline">
                          {!! csrf_field() !!}
                          <button title="{{ trans('message.form.Delete') }}" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_term_header') }}" data-message="{{ trans('message.table.delete_term') }}">
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
        <form action="{{url('payment/terms/add')}}" method="post" id="addUnit" class="form-horizontal">
            {!! csrf_field() !!}
          
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.terms') }}</label>

            <div class="col-sm-6">
              <input type="text" class="form-control" name="terms">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.invoice.due_day') }}</label>

            <div class="col-sm-6">
                <input type="number" class="form-control" name="days_before_due">
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
        <form action="{{ url('payment/terms/update') }}" method="post" id="editUnit" class="form-horizontal">
            {!! csrf_field() !!}
          
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.terms') }}</label>

            <div class="col-sm-6">
              <input type="text" class="form-control" name="terms" id="terms">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.invoice.due_day') }}</label>

            <div class="col-sm-6">
                <input type="number" class="form-control" name="days_before_due" id="days_before_due">
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
          <input type="hidden" name="id" id="term_id">

          
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
            "targets": 3,
            "orderable": false
            } ],
            
            "language": '{{Session::get('language')}}'
        });
        
      });

       $('#addUnit').validate({
            rules: {
                terms: {
                    required: true
                },
                days_before_due: {
                    required: true
                }                
            }
        });

       $('.edit_unit').on('click', function() {
        var id = $(this).attr("id");

        $.ajax({
            url: '{{ URL::to('payment/terms/edit')}}',
            data:{  // data that will be sent
                id:id
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
              
                $('#term_id').val(data.id);
                $('#terms').val(data.terms);
                $('#days_before_due').val(data.days_before_due);
                $('#defaults').val(data.defaults);

                $('#edit-unit').modal();
            }
        });

        $('#editUnit').validate({
            rules: {
                terms: {
                    required: true
                },
                days_before_due: {
                    required: true
                }                     
            }
        });

    });
    </script>
@endsection