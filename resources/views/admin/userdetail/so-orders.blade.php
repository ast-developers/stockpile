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
            <form class="form-horizontal" action='{{ url("user/sales-order-list/$user_id") }}' method="GET" id='salesHistoryReport'>
              
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
        "targets": 8,
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