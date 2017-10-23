<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">{{trans('message.header.email__temp_setting')}}</h3>
  </div>
  <div class="box-body no-padding" style="display: block;">
    <ul class="nav nav-pills nav-stacked">
      <li {{ isset($list_menu) &&  $list_menu == 'menu-5' ? 'class=active' : ''}} ><a href="{{ URL::to("customer-invoice-temp/5")}}">{{ trans('message.email.sales_order') }}</a></li>
      <li {{ isset($list_menu) &&  $list_menu == 'menu-4' ? 'class=active' : ''}} ><a href="{{ URL::to("customer-invoice-temp/4")}}">{{ trans('message.email.sales_invoice') }}</a></li>
      <li {{ isset($list_menu) &&  $list_menu == 'menu-3' ? 'class=active' : ''}} ><a href="{{ URL::to("customer-invoice-temp/3")}}"> {{ trans('message.email.purchase_order') }}</a></li>
      <li {{ isset($list_menu) &&  $list_menu == 'menu-1' ? 'class=active' : ''}}><a href="{{ URL::to("customer-invoice-temp/1")}}">{{ trans('message.extra_text.payment_notification') }} </a></li>
      <li {{ isset($list_menu) &&  $list_menu == 'menu-6' ? 'class=active' : ''}}><a href="{{ URL::to("customer-invoice-temp/6")}}">{{ trans('message.extra_text.packed_notification') }} </a></li>
      <li {{ isset($list_menu) &&  $list_menu == 'menu-2' ? 'class=active' : ''}} ><a href="{{ URL::to("customer-invoice-temp/2")}}">{{ trans('message.extra_text.shipment_notification') }}</a></li>    
    </ul>
  </div>
            <!-- /.box-body -->
</div>