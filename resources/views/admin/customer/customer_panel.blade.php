@extends('layouts.customer_panel')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{$totalOrder}}</h3>
              <p>{{ trans('message.customer_panel.orders')}}</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="{{url("customer-panel/order/$uid")}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{$totalInvoice}}</h3>

              <p>{{ trans('message.customer_panel.invoices')}}</p>
            </div>
            <div class="icon">
              <i class="fa fa-cart-plus"></i>
            </div>
            <a href="{{url("customer-panel/invoice/$uid")}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{$totalShipment}}</h3>

              <p>{{ trans('message.customer_panel.shipments')}}</p>
            </div>
            <div class="icon">
              <i class="fa fa-truck"></i>
            </div>
            <a href="{{url("customer-panel/shipment/$uid")}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{$totalBranch}}</h3>
              <p>{{ trans('message.customer_panel.branches')}}</p>
            </div>
            <div class="icon">
              <i class="fa fa-home"></i>
            </div>
            <a href="{{url("customer-panel/branch/$uid")}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
    </section>
@endsection
@section('js')
@endsection