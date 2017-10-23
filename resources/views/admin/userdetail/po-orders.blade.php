@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!--Default box -->
        @include('layouts.includes.user_menu')
        <h3>{{$user->real_name}}</h3> 
         <div class="box">
            <!-- /.box-header -->
            <div class="box-body">       
          <div class="col-md-12 col-xs-12">
              <div class="row">
            <form class="form-horizontal" action='{{ url("user/purchase-list/$user_id") }}' method="GET" id='salesHistoryReport'>
              
              <div class="col-md-2">
                  <label for="exampleInputEmail1">{{ trans('message.report.from') }}</label>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input class="form-control" id="from" type="text" name="from" value="<?= isset($from) ? $from : '' ?>" required>
                  </div>
              </div>
              
              <div class="col-md-2">
                  <label for="exampleInputEmail1">{{ trans('message.report.to') }}</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control" id="to" type="text" name="to" value="<?= isset($to) ? $to : '' ?>" required>
                  </div>
              </div>

              <div class="col-md-2">
                <label for="exampleInputEmail1">{{ trans('message.form.supplier') }}</label>
                <select class="form-control select2" name="supplier" id="supplier" required>
                <option value="all" <?= ($supplier=='all') ? 'selected' : ''?>>All</option>
                @foreach($supplierList as $data)
                  <option value="{{$data->supplier_id}}" <?= ($data->supplier_id == $supplier) ? 'selected' : ''?>>{{$data->supp_name}}</option>
                @endforeach
                </select>
              </div>

              <div class="col-md-2">
                <label for="exampleInputEmail1">{{ trans('message.form.location') }}</label>
                <select class="form-control select2" name="location" id="location" required>
                <option value="all" <?= ($location=='all') ? 'selected' : ''?>>All</option>
                @foreach($locationList as $data)
                  <option value="{{$data->loc_code}}" <?= ($data->loc_code == $location) ? 'selected' : ''?>>{{$data->location_name}}</option>
                @endforeach
                </select>
              </div>

              <div class="col-md-1">
                <label for="btn">&nbsp;</label>
                <button type="submit" name="btn" class="btn btn-primary btn-flat">{{ trans('message.extra_text.filter') }}</button>
              </div>
            </form>
          </div>
          </div>
          </div>
      </div>          

        <div class="box">
      
            <!-- /.box-header -->
            <div class="box-body">
            <div class="table-responsive">
              <table id="purchaseList" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="10%">{{ trans('message.table.invoice') }} #</th>
                    <th>{{ trans('message.table.supp_name') }}</th>
                    
                    <th>{{ trans('message.invoice.total') }}</th>
                    <th>{{ trans('message.table.ord_date') }}</th>
                    <th width="5%" class="hideColumn">{{ trans('message.table.action') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($purchData as $data)
                  <tr>
                    <td><a href="{{URL::to('/')}}/purchase/view-purchase-details/{{$data->order_no}}" >{{ $data->reference }}</a></td>
                    <td><a href="{{ url("edit-supplier/$data->supplier_id") }}">{{ $data->supp_name }}</a></td>
                    
                    <td>{{ Session::get('currency_symbol').number_format($data->total,2,'.',',') }}</td>
                    
                    <td>{{ formatDate($data->ord_date)}}</td>
                    <td class="hideColumn">
                    @if(!empty(Session::get('purchese_edit')))
                        <a  title="edit" class="btn btn-xs btn-primary" href='{{ url("purchase/edit/$data->order_no") }}'><span class="fa fa-edit"></span></a> &nbsp;
                    @endif
                    @if(!empty(Session::get('purchese_delete')))
                        <form method="POST" action="{{ url("purchase/delete/$data->order_no") }}" accept-charset="UTF-8" style="display:inline">
                            {!! csrf_field() !!}
                            <button title="delete" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_invoice_header') }}" data-message="{{ trans('message.table.delete_invoice') }}">
                                <i class="glyphicon glyphicon-trash"></i> 
                            </button>
                        </form>
                    @endif
                    </td>
                  </tr>
                 @endforeach
                  </tfoot>
                 </table>
            </div>
            
            </div>
            <!-- /.box-body -->
          </div>
        <!-- /.box-footer-->
    @include('layouts.includes.message_boxes')
    </section>
@endsection
@section('js')
    <script type="text/javascript">
  $(function () {
    $("#purchaseList").DataTable({
      "order": [],
      "columnDefs": [ {
        "targets": 4,
        "orderable": false
        } ],

        "language": '{{Session::get('dflt_lang')}}',
        "pageLength": '{{Session::get('row_per_page')}}'
    });
  });

  $(".select2").select2({});
    
    $('#from').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: '{{Session::get('date_format_type')}}'
    });
   

    $('#to').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: '{{Session::get('date_format_type')}}'
    });

    </script>
@endsection