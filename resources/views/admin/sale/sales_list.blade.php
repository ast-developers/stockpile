@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">

    <div class="box box-default">
      <div class="box-body">
        <div class="row">
          <div class="col-md-10">
           <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.invoices') }}</div>
          </div> 
          <div class="col-md-2">
            @if(!empty(Session::get('sales_add')))
              <a href="{{ url('sales/add') }}" class="btn btn-block btn-default btn-flat btn-border-orange"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.extra_text.new_sales_invoice') }}</a>
            @endif
          </div>
        </div>
      </div>
    </div>

      <div class="box">
        <div class="box-body">
                <ul class="nav nav-tabs cus" role="tablist">
                    
                    <li  class="active">
                      <a href='{{url("sales/list")}}' >{{ trans('message.extra_text.all') }}</a>
                    </li>
                    
                    <li>
                      <a href="{{url("sales/filtering")}}" >{{ trans('message.extra_text.filter') }}</a>
                    </li>

               </ul>
        </div>
       
      </div><!--Filtering Box End-->
      
      <!-- Default box -->
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
      <!-- /.box -->

    </section>

@include('layouts.includes.message_boxes')
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

    </script>
@endsection