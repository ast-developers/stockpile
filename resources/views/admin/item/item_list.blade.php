@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">

      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-8">
             <div class="top-bar-title padding-bottom">{{ trans('message.table.item') }}</div>
            </div> 
             @if (!empty(Session::get('item_add')))
            <div class="col-md-2 top-left-btn">
                <a href="{{ URL::to('itemimport') }}" class="btn btn-block btn-default btn-flat btn-border-purple"><span class="fa fa-upload"> &nbsp;</span>{{ trans('message.extra_text.import_new_item') }}</a>
            </div>

            <div class="col-md-2 top-right-btn">
                <a href="{{ url('create-item/item') }}" class="btn btn-block btn-default btn-flat btn-border-orange"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.extra_text.add_new_item') }}</a>
            </div>
            @endif
          </div>
        </div>
      </div>

      <!-- Top Box-->
      <div class="box">
        <div class="box-body">
          <div class="col-md-2 col-xs-6 border-right text-center">
              <h3 class="bold">{{count($itemData)}}</h3>
              <span class="text-info bold">{{ trans('message.table.item') }}</span>
          </div>
          <div class="col-md-2 col-xs-6 border-right text-center">
              <h3 class="bold">{{!empty($itemQuantity->total_item) ? $itemQuantity->total_item : 0 }}</h3>
              <span class="text-info bold">{{ trans('message.extra_text.quantity') }}</span>
          </div>


          <div class="col-md-3 col-xs-6 border-right text-center">
              <h3 class="bold">{{ Session::get('currency_symbol').number_format($costValueQtyOnHand,2,'.',',')}}</h3>
              <span class="text-info">{{ trans('message.report.on_hand_cost_value') }}</span>
          </div>
          <div class="col-md-3 col-xs-6 border-right text-center">
              <h3 class="bold">{{ Session::get('currency_symbol').number_format($retailValueOnHand ,2,'.',',')}}</h3>
              <span class="text-info">{{ trans('message.report.on_hand_retail_value') }} </span>
          </div>
          <div class="col-md-2 col-xs-6 text-center">
              <h3 class="bold">
                @if($profitValueOnHand<0)
                -{{Session::get('currency_symbol').number_format(abs($profitValueOnHand),2,'.',',')}}
                @else
                 {{Session::get('currency_symbol').number_format(abs($profitValueOnHand),2,'.',',')}}
                @endif
              </h3>
              <span class="text-info">{{ trans('message.report.on_hand_profit_value') }}</span>
          </div>


        </div>
        <br>
      </div><!--Top Box End-->

      <!-- Default box -->
      <div class="box">
      
            <div class="box-header">
              <a href="{{ URL::to('itemdownloadcsv/csv') }}"><button class="btn btn-default btn-flat btn-border-info"><span class="fa fa-download"> &nbsp;</span>{{ trans('message.table.download_csv') }}</button></a>
            </div>
  
            <!-- /.box-header -->
            <div class="box-body">
              <table id="itemList" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">{{ trans('message.table.picture') }}</th>
                  <th class="text-center">{{ trans('message.table.name') }}</th>
                  <th class="text-center">{{ trans('message.table.category') }}</th>
                  <th class="text-center">{{ trans('message.table.on_hand') }}</th>
                  <th class="text-center">{{ trans('message.table.purchase') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.table.wholesale') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.table.retail') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.form.status') }}</th>
                  <th width="14%" class="text-center">{{ trans('message.table.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($itemData as $data)
                <tr>
                  <td width="5%" class="text-center">
                  <a href="{{ url("edit-item/item-info/$data->item_id") }}">
                    @if (!empty($data->img))
                    <img src='{{url("public/uploads/itemPic/$data->img")}}' alt="" width="50" height="50">
                    @else
                    <img src='{{url("public/uploads/default-image.png")}}' alt="" width="50" height="50">
                    @endif
                    </a>
                  </td>
                  <td class="text-center"><a href="{{ url("edit-item/item-info/$data->item_id") }}">{{ $data->description }}</a></td>
                  <td class="text-center">{{ $data->category_name }}</td>
                  <td class="text-center">{{ $data->item_qty }}</td>
                  <td class="text-center">{{ $data->purchase_price }}</td>
                  <td class="text-center">{{ $data->whole_sale_price }}</td>
                  <td class="text-center">{{ $data->retail_sale_price }}</td>
                  <td class="text-center">
                  @if($data->inactive == 0)
                    <span class='label label-success'>{{ trans('message.table.active') }}</span>
                  @else
                    <span class='label label-danger'>{{ trans('message.table.inactive') }}</span>
                  @endif
                  </td>
                  
                  <td class="text-center">
                  @if (!empty(Session::get('item_edit')))
                      <a title="edit" class="btn btn-xs btn-primary" href='{{ url("edit-item/item-info/$data->item_id") }}'><span class="fa fa-edit"></span></a> &nbsp;
                  @endif
                  @if (!empty(Session::get('item_delete')))
                      <form method="POST" action="{{ url("item/delete/$data->item_id") }}" accept-charset="UTF-8" style="display:inline">
                          {!! csrf_field() !!}
                          <button title="{{ trans('message.form.Delete') }}" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_item_header') }}" data-message="{{ trans('message.table.delete_item') }}">
                              <i class="glyphicon glyphicon-trash"></i> 
                          </button> &nbsp;
                      </form>
                  @endif
                  </td>
                </tr>
               @endforeach
                </tfoot>
              </table>
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
    $("#itemList").DataTable({
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