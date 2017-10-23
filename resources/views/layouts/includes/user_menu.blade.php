<div class="box">
   <div class="panel-body">
        <ul class="nav nav-tabs cus" role="tablist">
            <li  class="{{ isset($po_status) ? $po_status : '' }}">
              <a href='{{url("user/purchase-list/$user_id")}}' >{{ trans('message.extra_text.purchase_orders') }}</a>
            </li>

            <li class="{{ isset($so_status) ? $so_status : '' }}">
              <a href='{{url("user/sales-order-list/$user_id")}}' >{{ trans('message.extra_text.sales_order') }}</a>
            </li>
            
            <li class="{{ isset($invoice) ? $invoice : '' }}">
              <a href='{{url("user/sales-invoice-list/$user_id")}}' >{{ trans('message.extra_text.invoices') }}</a>
            </li>

            <li class="{{ isset($payment) ? $payment : '' }}">
              <a href='{{url("user/user-payment-list/$user_id")}}' >{{ trans('message.extra_text.payments') }}</a>
            </li>
            
            <li class="{{ isset($transfer) ? $transfer : '' }}">
              <a href='{{url("user/user-transfer-list/$user_id")}}' >{{ trans('message.sidebar.stock-move-list') }}</a>
            </li>

       </ul>
      <div class="clearfix"></div>
   </div>
</div> 