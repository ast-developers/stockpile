@extends('layouts.customer_panel')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
              <div class="box-body">
                <div class="row">
                  <div class="col-md-4">
                   <strong class="text-info">{{ trans('message.invoice.payment_no').' # '.sprintf("%04d", $paymentInfo->id) }}</strong>
                  </div>
                </div>
              </div>

              <div class="box-body">
                <div class="row">
                  <div class="col-md-6">
                    <strong>{{ $settings[8]->value }}</strong>
                    <h5>{{ $settings[11]->value }}</h5>
                    <h5>{{ $settings[12]->value }}, {{ $settings[13]->value }}</h5>
                    <h5>{{ $settings[15]->value }}, {{ $settings[14]->value }}</h5>
                  </div>
                  <div class="col-md-6 text-right">
                    <h5>{{ $paymentInfo->name }}</h5>
                    <h5>{{ $paymentInfo->br_address }}</h5>
                    <h5>{{ $paymentInfo->billing_street }}</h5>
                    <h5>{{ $paymentInfo->billing_city }}, {{ $paymentInfo->billing_state }}</h5>
                    <h5>{{ $paymentInfo->billing_country_id }}, {{ $paymentInfo->billing_zip_code }}</h5>                    
                  </div>
                </div>
                <h3 class="text-center">PAYMENT RECEIPT</h3>
                  <div class="row">
                    <div class="col-md-6">
                      <h5>{{ trans('message.invoice.payment_date')}} : {{ formatDate($paymentInfo->payment_date) }}</h5>
                      <h5>{{ trans('message.invoice.payment_on')}} : {{ $paymentInfo->payment_method }}</h5>
                      <div class="well well-lg label-primary text-center"><strong>{{ trans('message.invoice.total_amount')}}<br/>{{ Session::get('currency_symbol').number_format($paymentInfo->amount,2,'.',',') }}</strong></div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="table-responsive">
                          <table class="table table-bordered">
                            <tbody>
                              <tr class="tbl_header_color dynamicRows">
                                <th width="20%" class="text-center">{{ trans('message.invoice.order_no') }}</th>
                                <th width="20%" class="text-center">{{ trans('message.invoice.invoice_no') }}</th>
                                <th width="20%" class="text-center">{{ trans('message.invoice.invoice_date') }}</th>
                                <th width="20%" class="text-center">{{ trans('message.invoice.invoice_amount') }}</th>
                                <th width="20%" class="text-center">{{ trans('message.invoice.paid_amount') }}</th>
                              </tr>
                              <tr>
                                <td width="20%" class="text-center">{{ $paymentInfo->order_reference }}</td>
                                <td width="20%" class="text-center">{{ $paymentInfo->invoice_reference }}</td>
                                <td width="20%" class="text-center">{{ formatDate($paymentInfo->invoice_date) }}</td>
                                <td width="20%" class="text-center">{{ Session::get('currency_symbol').number_format($paymentInfo->invoice_amount,2,'.',',') }}</td>
                                <td width="20%" class="text-center">{{ Session::get('currency_symbol').number_format($paymentInfo->amount,2,'.',',') }}</td>
                              </tr>
                            </tbody>
                          </table>
                      </div>
                      </div>
                  </div>
              </div>
            </div>
        </div>
      </div>
    </section>
@endsection

@section('js')
  <script type="text/javascript">
  </script>
@endsection