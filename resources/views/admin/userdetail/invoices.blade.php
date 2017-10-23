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
            <form class="form-horizontal" action='{{ url("user/sales-invoice-list/$user_id") }}' method="GET" id='salesHistoryReport'>
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
                <label for="exampleInputEmail1">{{ trans('message.form.customer') }}</label>
                <select class="form-control select2" name="customer" id="customer" required>
                <option value="all" <?= ($customer=='all') ? 'selected' : ''?>>All</option>
                @foreach($customerList as $data)
                  <option value="{{$data->debtor_no}}" <?= ($data->debtor_no == $customer) ? 'selected' : ''?>>{{$data->name}}</option>
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
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ trans('message.table.invoice') }}</th>
                    <th>{{ trans('message.table.order_no') }}</th>
                    <th>{{ trans('message.table.customer_name') }}</th>
                    <th>{{ trans('message.table.total_price') }}</th>
                    <th>{{ trans('message.table.paid_amount') }}</th>
                    <th>{{ trans('message.table.paid_status') }}</th>
                    <th>{{ trans('message.invoice.invoice_date') }}</th>
                    <th width="5%">{{ trans('message.table.action') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($salesData as $data)
                  <tr>
                    <td><a href="{{URL::to('/')}}/invoice/view-detail-invoice/{{$data->order_reference_id.'/'.$data->order_no}}">{{$data->reference }}</a></td>
                    <td><a href="{{URL::to('/')}}/order/view-order-details/{{$data->order_reference_id}}">{{ $data->order_reference }}</a></td>
                    <td><a href="{{url("customer/edit/$data->debtor_no")}}">{{ $data->cus_name }}</a></td>
                    <td>{{ Session::get('currency_symbol').number_format($data->total,2,'.',',') }}</td>
                    <td>{{ Session::get('currency_symbol').number_format($data->paid_amount,2,'.',',') }}</td>
  
                    @if($data->paid_amount == 0)
                      <td><span class="label label-danger">{{ trans('message.invoice.unpaid')}}</span></td>
                    @elseif($data->paid_amount > 0 && $data->total > $data->paid_amount )
                      <td><span class="label label-warning">{{ trans('message.invoice.partially_paid')}}</span></td>
                    @elseif($data->paid_amount<=$data->paid_amount)
                      <td><span class="label label-success">{{ trans('message.invoice.paid')}}</span></td>
                    @endif

                    <td>{{formatDate($data->ord_date)}}</td>
                    <td>
                    @if(!empty(Session::get('sales_edit')))
                        <a  title="edit" class="btn btn-xs btn-primary" href='{{ url("sales/edit/$data->order_no") }}'><span class="fa fa-edit"></span></a> &nbsp;
                    @endif
                    @if(!empty(Session::get('sales_delete')))
                       <form method="POST" action="{{ url("invoice/delete/$data->order_no") }}" accept-charset="UTF-8" style="display:inline">
                          {!! csrf_field() !!}
                          <button class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.invoice.delete_invoice') }}" data-message="{{ trans('message.invoice.delete_invoice_confirm') }}">
                             <i class="fa fa-remove" aria-hidden="true"></i>
                          </button>
                      </form> 
                      @endif
                    </td>
                  </tr>
                 @endforeach
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
    $("#example1").DataTable({
      "order": [],
      "columnDefs": [ {
        "targets": 7,
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