@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-default">
        <!-- /.box-header -->
        <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div>{{ trans('message.form.source') }} : {{getDestinatin($Info->source)}}</div>
                <div>{{ trans('message.form.destination') }} : {{getDestinatin($Info->destination)}}</div>
                <div>{{ trans('message.form.date') }} : {{formatDate($Info->transfer_date)}}</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
              <div class="box-header text-center">
                <h3 class="box-title">{{ trans('message.form.stock_move') }}</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body no-padding">
                <div class="table-responsive">
                <table class="table table-bordered">
                  <tbody>
                  <tr class="tbl_header_color dynamicRows">
                    <th width="75%" class="text-center">{{ trans('message.form.item_name') }}</th>
                    <th width="20%" class="text-center">{{ trans('message.table.quantity') }}</th>
                  </tr>
                  <?php
                    $sum = 0;
                  ?>
                  @foreach($List as $value)
                  <?php 
                    $sum += $value->qty;
                  ?>
                  <tr><td class="text-center">{{getItemName($value->stock_id)}}</td><td class="text-center">{{$value->qty}}</td></tr>
                  @endforeach
                  <tr><td align="right"><strong>{{ trans('message.invoice.total') }}</strong></td><td style="font-weight:bold;text-align:center" width="20%">{{$sum}}</td></tr>
                  </tbody>
                </table>
                </div>
                <br><br>
              </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div><strong>{{ trans('message.table.note') }} :</strong> </div>
                <div>{{$Info->note}}</div>
            </div>
        </div>

      </div>
          <!-- /.row -->
    </div>

    </section>
@endsection
@section('js')
<script type="text/javascript">
</script>
@endsection