@extends('layouts.app')
@section('content')

    <!-- Main content -->
    <section class="content">

    <div class="box box-default">
      <div class="box-body">
        <div class="row">
          <div class="col-md-10">
           <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.sales_orders') }}</div>
          </div> 
          <div class="col-md-2">
            @if(!empty(Session::get('order_add')))
              <a href="{{ url('order/add') }}" class="btn btn-block btn-default btn-flat btn-border-orange"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.form.add_new_order') }}</a>
            @endif
          </div>
        </div>
      </div>
    </div>
      <div class="box">
        <div class="box-body">

                <ul class="nav nav-tabs cus" role="tablist">
                    
                    <li>
                      <a href='{{url("order/list")}}' >{{ trans('message.extra_text.all') }}</a>
                    </li>
                    
                    <li class="active">
                      <a href="{{url("order/filtering")}}" >{{ trans('message.extra_text.filter') }}</a>
                    </li>

               </ul>

          <form class="form-horizontal" action="{{ url('order/filtering') }}" method="GET" id='orderListFilter'>
          <div class="col-md-12">
            <div class="row">

               <div class="col-md-2">
               <div class="form-group" style="margin-right: 5px">
                  <label for="exampleInputEmail1">{{ trans('message.report.from') }}</label>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input class="form-control" id="from" type="text" name="from" value="{{$from}}" required>
                  </div>
                </div>
               </div> 

               <div class="col-md-2">
               <div class="form-group" style="margin-right: 5px">
                  <label for="exampleInputEmail1">{{ trans('message.report.to') }}</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control" id="to" type="text" name="to" value="{{$to}}" required>
                  </div>
                  </div>
               </div> 

               <div class="col-md-2">
                 <div class="form-group" style="margin-right: 5px">
                  <label for="sel1">{{ trans('message.report.product') }}</label>
                  <div class="input-group">
                  <select class="form-control select2" name="product" id="product">
                    <option value="">All</option>
                    @if(!empty($productList))
                    @foreach($productList as $productItem)

                    <option value="{{$productItem->stock_id}}" <?= ($productItem->stock_id == $item) ? 'selected' : ''?>>{{$productItem->description}}</option>
                    @endforeach
                    @endif
                  </select>
                  </div>
                </div>
               </div>
               <div class="col-md-2">
                 <div class="form-group" style="margin-right: 5px">
                  <label for="sel1">{{ trans('message.form.customer') }}</label>
                  <div class="input-group">
                  <select class="form-control select2" name="customer" id="customer">
                    <option value="">All</option>
                    @if(!empty($customerList))
                    @foreach($customerList as $customerItem)
                    <option value="{{$customerItem->debtor_no}}" <?= ($customerItem->debtor_no == $customer) ? 'selected' : ''?>>{{$customerItem->name}}</option>
                    @endforeach
                    @endif
                  </select>
                  </div>
                </div>
               </div>

               <div class="col-md-2">
                 <div class="form-group" style="margin-right: 5px">
                  <label for="location">{{ trans('message.form.location') }}</label>
                  <div class="input-group">
                  <select class="form-control select2" name="location" id="location">
                    <option value="">All</option>
                    @if(!empty($locationList))
                    @foreach($locationList as $locationItem)
                    <option value="{{$locationItem->loc_code}}" <?= ($locationItem->loc_code == $location) ? 'selected' : ''?>>{{$locationItem->location_name}}</option>
                    @endforeach
                    @endif
                  </select>
                  </div>
                </div>
               </div> 

            </div>
           
             <div class="row">
             <div class="col-md-1">
             <div class="form-group">
                <div class="input-group">
                <button type="submit" name="btn" class="btn btn-primary btn-flat">{{ trans('message.extra_text.filter') }}</button>
                </div>
              </div>
              </div>
             </div>
          </div>
          </form>
        </div>
      </div><!--Filtering Box End-->


      <div class="box">
            <div class="box-body">
              <div class="table-responsive">
                <table id="orderList" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ trans('message.table.order') }} #</th>
                    <th>{{ trans('message.table.customer_name') }}</th>
                    <th>{{ trans('message.extra_text.quantity') }}</th>
                    <th>{{ trans('message.invoice.invoiced') }}</th>
                    <th>{{ trans('message.invoice.packed') }}</th>
                    <th>{{ trans('message.invoice.paid') }}</th>
                    <th>{{ trans('message.table.total') }}</th>
                    <th>{{ trans('message.table.ord_date') }}</th>
                    <th width="5%">{{ trans('message.table.action') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($salesData as $data)
                 @if($data->ordered_quantity>0)
                  <tr>
                    <td><a href="{{URL::to('/')}}/order/view-order-details/{{$data->order_no}}">{{$data->reference }}</a></td>
                    <td><a href="{{URL::to('/')}}/customer/edit/{{$data->debtor_no}}">{{ $data->name }}</a></td>
                    <td>{{ $data->ordered_quantity }}</td>

                    @if( $data->invoiced_quantity == 0 )
                      <td><span class="fa fa-circle-thin"></span></td>
                    @elseif(abs($data->ordered_quantity) - abs($data->invoiced_quantity)== 0)
                      <td><span class="fa fa-circle"></span></td>
                    @elseif(abs($data->ordered_quantity) - abs($data->invoiced_quantity)>0)
                      <td><span class="glyphicon glyphicon-adjust"></span></td>
                    @endif


                    @if( $data->packed_qty == 0 )
                    <td><span class="fa fa-circle-thin"></span></td>
                    @elseif(abs($data->ordered_quantity) - abs($data->packed_qty)== 0)
                    <td><span class="fa fa-circle"></span></td>
                    @elseif(abs($data->ordered_quantity) - abs($data->packed_qty)>0)
                    <td><span class="glyphicon glyphicon-adjust"></span></td>
                    @endif

                    @if( $data->paid_amount == 0 )
                      <td><span class="fa fa-circle-thin"></span></td>
                    @elseif(abs($data->order_amount) - abs($data->paid_amount) == 0)
                      <td><span class="fa fa-circle"></span></td>
                    @elseif(abs($data->order_amount) - abs($data->paid_amount)>0)
                      <td><span class="glyphicon glyphicon-adjust"></span></td>
                    @elseif(abs($data->order_amount) - abs($data->paid_amount)<0)
                      <td><span class="fa fa-circle"></span></td>
                    @endif

                    <td>{{ Session::get('currency_symbol').number_format($data->order_amount,2,'.',',') }}</td>
                    <td>{{formatDate($data->ord_date)}}</td>
                    <td>
                    
                    @if(!empty(Session::get('order_edit')))
                        

                        <a  title="Edit" class="btn btn-xs btn-primary" href='{{ url("order/edit/$data->order_no") }}'><span class="fa fa-edit"></span></a> &nbsp;

                    @endif
                    @if(!empty(Session::get('order_delete')))
                        <form method="POST" action="{{ url("order/delete/$data->order_no") }}" accept-charset="UTF-8" style="display:inline">
                            {!! csrf_field() !!}
                            
                            <button title="delete" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.invoice.delete_order') }}" data-message="{{ trans('message.invoice.delete_order_confirm') }}">
                                <i class="glyphicon glyphicon-trash"></i> 
                            </button>
                        </form>
                    @endif
                    </td>
                  </tr>
                  @endif
                 @endforeach
                  </tfoot>
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
    $('.select2').select2({});
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
    
  $(function () {
    $("#orderList").DataTable({
      "order": [],
      "columnDefs": [ {
        "targets": 8,
        "orderable": false
        } ],

        "language": '{{Session::get('dflt_lang')}}',
        "pageLength": '{{Session::get('row_per_page')}}'
    });
    
  });

    </script>
@endsection