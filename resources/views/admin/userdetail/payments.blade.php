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
            <form class="form-horizontal" action='{{ url("user/user-payment-list/$user_id") }}' method="GET" id='salesHistoryReport'>
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
                <table id="paymentList" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ trans('message.invoice.payment_no') }}</th>
                    <th>{{ trans('message.table.order_no') }}</th>
                    <th>{{ trans('message.invoice.invoice_no') }}</th>
                    <th>{{ trans('message.invoice.customer_name') }}</th>
                    <th>{{ trans('message.extra_text.payment_method') }}</th>
                    <th>{{ trans('message.invoice.amount') }}</th>
                    <th>{{ trans('message.invoice.payment_date') }}</th>
                    <th>{{ trans('message.invoice.action') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($paymentList as $data)
                  <tr>
                    <td><a href="{{ url("payment/view-receipt/$data->id") }}">{{sprintf("%04d", $data->id)}}</a></td>
                    <td><a href="{{ url("order/view-order-details/$data->order_id") }}">{{$data->order_reference}}</a></td>
                    <td><a href="{{ url("invoice/view-detail-invoice/$data->order_id/$data->invoice_id") }}">{{ $data->invoice_reference }}</a></td>
                    <td><a href="{{url("customer/edit/$data->customer_id")}}">{{ $data->name }}</a></td>
                    <td>{{ $data->pay_type }}</td>
                    <td>{{ Session::get('currency_symbol').number_format($data->amount,2,'.',',') }}</td>
                    <td>{{formatDate($data->payment_date)}}</td>
                    <td>
                    @if(!empty(Session::get('payment_edit')))
                        <a  title="View" class="btn btn-xs btn-primary" href='{{ url("payment/view-receipt/$data->id") }}'><span class="fa fa-eye"></span></a> &nbsp;
                    @endif
                    @if(!empty(Session::get('payment_delete')))
                        <form method="POST" action="{{ url("payment/delete") }}" accept-charset="UTF-8" style="display:inline">
                            {!! csrf_field() !!}
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <button title="delete" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.invoice.delete_payment_header') }}" data-message="{{ trans('message.invoice.delete_payment') }}">
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
    $("#paymentList").DataTable({
      "order": [],
      "columnDefs": [ {
        "targets": 6,
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