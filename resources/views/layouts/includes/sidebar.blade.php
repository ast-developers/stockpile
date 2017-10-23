<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      
      <ul class="sidebar-menu">
        
        <li <?= $menu == 'dashboard' ? ' class="active"' : ''?> >
          <a href="{{ url('dashboard') }}">
            <i class="fa fa-dashboard"></i> <span>{{ trans('message.sidebar.dashboard') }} </span>
          </a>
        </li>

        <li <?= $menu == 'customer' ? ' class="active"' : ''?> >
          <a href="{{url('customer/list')}}">
            <i class="fa fa-users"></i> <span>{{ trans('message.extra_text.customers') }}</span>
          </a>
        </li>

        <li <?= $menu == 'item' ? ' class="active"' : ''?> >
          <a href="{{url('item')}}">
            <i class="fa fa-cubes"></i>
            <span>{{ trans('message.sidebar.item') }}</span>
          </a>
        </li>
        
        <li <?= $menu == 'sales' ? ' class="treeview active"' : 'treeview'?> >
          <a href="#">
            <i class="fa fa-list-ul"></i>
            <span>{{ trans('message.sidebar.sales') }}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?= isset($sub_menu) && $sub_menu == 'order/list' ? ' class="active"' : ''?> >
              <a href="{{url('order/list')}}">
                <span>{{ trans('message.table.sales_order_no') }}</span>
              </a>
            </li>
            <li <?= isset($sub_menu) && $sub_menu == 'sales/direct-invoice' ? ' class="active"' : ''?> >
              <a href="{{url('sales/list')}}">
                
                <span>{{ trans('message.table.invoices') }}</span>
              </a>
            </li>  
            <li <?= isset($sub_menu) && $sub_menu == 'payment/list' ? ' class="active"' : ''?> >
              <a href="{{url('payment/list')}}">
                
                <span>{{ trans('message.extra_text.payments') }}</span>
              </a>
            </li>
            <li <?= isset($sub_menu) && $sub_menu == 'shipment/list' ? ' class="active"' : ''?> >
              <a href="{{url('shipment/list')}}">
              
                <span>{{ trans('message.extra_text.shipments') }}</span>
              </a>
            </li> 
          </ul>
        </li>

        <li <?= $menu == 'supplier' ? ' class="active"' : ''?> >
          <a href="{{url('supplier')}}">
            <i class="fa fa-users"></i> <span>{{ trans('message.extra_text.supplier') }}</span>
          </a>
        </li>

        <li <?= $menu == 'purchase' ? ' class="active"' : ''?> >
          <a href="{{url('purchase/list')}}">
            <i class="fa fa-shopping-cart"></i> <span>{{ trans('message.extra_text.purchases') }}</span>
          </a>
        </li>

        <li <?= $menu == 'transfer' ? ' class="active"' : ''?> >
          <a href="{{url('transfer/list')}}">
            <i class="fa fa-truck"></i> <span>{{ trans('message.sidebar.stock-move-list') }}</span>
          </a>
        </li>

        <li <?= $menu == 'report' ? ' class="treeview active"' : 'treeview'?> >
          <a href="#">
            <i class="fa fa-bar-chart"></i>
            <span>{{ trans('message.sidebar.report') }}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            
            <li <?= isset($sub_menu) && $sub_menu == 'report/inventory-stock-on-hand' ? ' class="active"' : ''?> >
              <a href="{{url('report/inventory-stock-on-hand')}}">
                
                <span>{{ trans('message.sidebar.inventory_stock_on_hand') }}</span>
              </a>
            </li>

            <li <?=isset($sub_menu) && $sub_menu == 'report/sales-report' ? ' class="active"' : ''?> >
              <a href="{{url('report/sales-report')}}">
                
                <span>{{ trans('message.sidebar.sales_report') }}</span>
              </a>
            </li>

            <li <?=isset($sub_menu) && $sub_menu == 'sales-history-report' ? ' class="active"' : ''?> >
              <a href="{{url('report/sales-history-report')}}">
                
                <span>{{ trans('message.sidebar.sales_history_report') }}</span>
              </a>
            </li>

            <li <?=isset($sub_menu) && $sub_menu == 'purchase-report' ? ' class="active"' : ''?> >
              <a href="{{url('report/purchase-report')}}">
                <span>{{ trans('message.purchase_report.purchase_report') }}</span>
              </a>
            </li>

            <li <?=isset($sub_menu) && $sub_menu == 'member-report' ? ' class="active"' : ''?> >
              <a href="{{url('report/member-report')}}">
                <span>{{ trans('message.purchase_report.member_report') }}</span>
              </a>
            </li>


          </ul>
        </li>
        <li <?= $menu == 'setting' ? ' class="treeview active"' : 'treeview'?> >
          <a href="#">
            <i class="fa fa-gears"></i>
            <span>{{ trans('message.form.settings') }}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?=isset($sub_menu) && $sub_menu == 'company' ? ' class="active"' : ''?> >
              <a href="{{url('company/setting')}}">
                <span>{{ trans('message.table.company_details') }}</span>
              </a>
            </li>

            <li <?= isset($sub_menu) && $sub_menu == 'general' ? ' class="active"' : ''?> >
              <a href="{{url('item-category')}}">
                <span>{{ trans('message.table.general_settings') }}</span>
              </a>
            </li>
            
            <li <?=isset($sub_menu) && $sub_menu == 'finance' ? ' class="active"' : ''?> >
              <a href="{{url('tax')}}">
                <span>{{ trans('message.table.finance') }}</span>
              </a>
            </li>

            @if(!empty(Session::get('emailtemp')))
            <li <?=isset($sub_menu) && $sub_menu == 'mail-temp' ? ' class="active"' : ''?> >
              <a href="{{url('customer-invoice-temp/1')}}">
                <span>{{ trans('message.email.email_template') }}</span>
              </a>
            </li>
            @endif

            @if(!empty(Session::get('preference')))
            <li <?=isset($sub_menu) && $sub_menu == 'preference' ? ' class="active"' : ''?> >
              <a href="{{url('setting-preference')}}">
                <span>{{ trans('message.table.preference') }}</span>
              </a>
            </li>
            @endif
            <li <?=isset($sub_menu) && $sub_menu == 'barcode' ? ' class="active"' : ''?> >
              <a href="{{url('barcode/create')}}">
                <span>{{ trans('message.barcode.print_menu') }}</span>
              </a>
            </li>
          </ul>
        </li>
     
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>