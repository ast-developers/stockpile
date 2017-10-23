<div class="col-md-4 left-padding-col4">
    <div class="box box-default">
      <div class="box-header text-center">
        <h5 class="text-left text-info"><b>{{ trans('message.table.order_no') }} # <a href="{{URL::to('/')}}/order/view-order-details/{{$orderInfo->order_no}}">{{$orderInfo->reference}}</a></b></h5>
      </div>
    </div>
    <!--Start-->
    <div class="box box-default">
      <div class="box-header text-center">
        <strong>{{ trans('message.table.invoices') }}</strong>
      </div>
      <div class="box-body">
        @if(!empty($invoiceList))
        <table class="table table-bordered">
          <thead>
            <tr>
              <th width="65%" class="left">{{ trans('message.invoice.invoice_no') }}</th>
              <th width="35%" class="text-right">{{ trans('message.invoice.amount') }}({{ Session::get('currency_symbol')}})</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $invoiceTotal = 0;
            ?>
            @foreach($invoiceList as $key=>$invoice)
            <tr>
              <td align="left">
                <a href="{{URL::to('/')}}/invoice/view-detail-invoice/{{$orderInfo->order_no.'/'.$invoice->order_no}}">
                    @if($invoice->total <= $invoice->paid_amount)
                    <i class="fa fa-check" aria-hidden="true"></i>
                     @else
                     <i class="fa fa-chevron-right" aria-hidden="true"></i>
                     @endif
                  {{$invoice->reference}}
                </a>

                @if($invoice->total <= $invoice->paid_amount && $invoice->total > 0)
                <span class="badge"> {{ trans('message.invoice.paid') }}</span>
                @endif

              </td>
              <td align="right">{{ number_format($invoice->total,2,'.',',')}}</td>
            </tr>
            <?php
            $invoiceTotal += $invoice->total;
            ?>
            @endforeach
              <td colspan="1" align="right"><strong>{{ trans('message.invoice.total') }}</stron></td><td align="right"><strong>{{ Session::get('currency_symbol').number_format($invoiceTotal,2,'.',',') }}</strong></td>
          </tbody>
        </table>
        @else
        <h5 class="text-center">{{ trans('message.invoice.no_invoice') }}</h5>
        @endif
      </div>
        @if(($orderQty+$invoiceQty) > 0  )
        @if(!empty(Session::get('sales_add')))
        <div class="box-body">
          <div class="row">
            <div class="col-md-6 btn-block-left-padding">
              <a href="{{URL::to('/')}}/order/manual-invoice-create/{{$orderInfo->order_no}}" title="{{ trans('message.table.manual_invoice') }}" class="btn btn-success btn-flat btn-block ">{{ trans('message.table.manual_invoice_title') }}</a>
            </div>
            <div class="col-md-6 btn-block-right-padding">
              <a href="{{URL::to('/')}}/order/auto-invoice-create/{{$orderInfo->order_no}}" title="{{ trans('message.table.automatic_invoice') }}" class="btn bg-orange btn-flat btn-block">{{ trans('message.table.automatic_invoice_title') }}</a>
            </div>
          </div>
        </div>
        @endif
        @endif
    </div> 
    <!--END-->
    <div class="box box-default">
      <div class="box-header text-center">
        <strong>{{ trans('message.invoice.payment_list') }}</strong>
      </div>
      <div class="box-body">
        @if(!empty($paymentsList))
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center">{{ trans('message.invoice.payment_no') }}</th>
              <th>{{ trans('message.invoice.invoice_no') }}</th>
              <th>{{ trans('message.extra_text.method') }}</th>
              <th>{{ trans('message.invoice.amount') }}({{ Session::get('currency_symbol')}})</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sumInvoice = 0;
            ?>
            @foreach($paymentsList as $payment)
            <tr>
              <td align="center"><a href="{{ url("payment/view-receipt/$payment->id") }}"><i class="fa fa-chevron-right" aria-hidden="true"></i>&nbsp;{{sprintf("%04d", $payment->id)}}</a></td>
              <td align="center">{{$payment->invoice_reference}}</td>
              <td align="center">{{$payment->name}}</td>
              <td align="right">{{number_format($payment->amount,2,'.',',')}}</td>
            </tr>
            <?php
            $sumInvoice += $payment->amount;
            ?>
            @endforeach
              <td colspan="3" align="right"><strong>{{ trans('message.invoice.total') }}</stron></td><td align="right"><strong>{{Session::get('currency_symbol').number_format($sumInvoice,2,'.',',')}}</strong></td>
          </tbody>
        </table>
        @else
        <h5 class="text-center">{{ trans('message.invoice.no_payment') }}</h5>
        @endif
        <?php
          $totalDueAmount = getTotalPaidAmountByOrder($orderInfo->reference,$orderInfo->order_no);
        ?>
        @if($totalDueAmount>0)
          <div class="btn-block-left-padding" style="margin-top:10px;">
            <a href="{{URL::to('/')}}/payment/pay-all/{{$orderInfo->order_no}}" title="{{ trans('message.extra_text.pay_all') }}" class="btn btn-success btn-flat btn-block">{{ trans('message.extra_text.pay_all') }}</a>
          </div>
        @endif
      </div>
    </div>
    <div class="box box-default">
      <div class="box-header text-center">
        <strong>{{ trans('message.invoice.shipment_list') }}</strong>
      </div>
      <div class="box-body">
        @if(!empty($shipmentList))
        <table class="table table-bordered">
          <thead>
            <tr>
              <th width="34%" class="text-center">{{ trans('message.invoice.shift_no') }}</th>
              <th width="33%" class="text-center">{{ trans('message.extra_text.shift_status') }}</th>
              <th width="33%" class="text-center">{{ trans('message.invoice.shipment_qty') }}</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sumShipment = 0;
            ?>
            @foreach($shipmentList as $key=>$shipment)
            <tr>
              <td align="center"><a href="{{ url('shipment/view-details/'.$orderInfo->order_no.'/'.$shipment->shipment_id) }}"><i class="fa fa-chevron-right" aria-hidden="true"></i>&nbsp;{{sprintf("%04d", $shipment->shipment_id)}}</a></td>
              <td align="center">{{getShipmentStatus($shipment->shipment_id)}}</td>
              <td align="center">{{$shipment->total}}</td>
            </tr>
            <?php
            $sumShipment += $shipment->total;
            ?>
            @endforeach
              <td colspan="2" align="right"><strong>{{ trans('message.invoice.total') }}</stron></td><td align="center"><strong>{{$sumShipment}}</strong></td>
          </tbody>
        </table>
        @else
        <h5 class="text-center">{{ trans('message.invoice.no_shipment') }}</h5>
        @endif
      </div>
        @if($shipmentStatus=='available')

         @if(!empty(Session::get('shipment_add')))
        <div class="box-body">
          <div class="row">
            <div class="col-md-6 btn-block-left-padding">
              <a href="{{URL::to('/')}}/shipment/add/{{$orderInfo->order_no}}" title="{{ trans('message.extra_text.manual_packing') }}" class="btn btn-success btn-flat btn-block">{{ trans('message.table.manual_invoice_title') }}</a>
            </div>
            <div class="col-md-6 btn-block-right-padding">
              <a href="{{URL::to('/')}}/shipment/create-auto-shipment/{{$orderInfo->order_no}}" title="{{ trans('message.extra_text.auto_packing') }}" class="btn bg-orange btn-flat btn-block">{{ trans('message.table.automatic_invoice_title') }}</a>
            </div>
          </div>
        </div>
        @endif
        @endif
    </div>       
</div>