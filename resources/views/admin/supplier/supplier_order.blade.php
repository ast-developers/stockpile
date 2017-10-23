@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">

    <div class="box box-default">
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
           <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.supplier_single') }}</div>
          </div> 
        </div>
      </div>
    </div>
    <h3>{{$supplierData->supp_name}}</h3>
        <div class="box">
           <div class="panel-body">
                <ul class="nav nav-tabs cus" role="tablist">
                    <li>
                      <a href="{{url("edit-supplier/$supplierid")}}" >{{ trans('message.sidebar.profile') }}</a>
                    </li>
                    <li class="active">
                      <a href="{{url("supplier/orders/$supplierid")}}" >{{ trans('message.extra_text.purchase_orders') }}</a>
                    </li>
               </ul>
              <div class="clearfix"></div>
           </div>
        </div>

      <!-- Default box -->
        
        <div class="box">
          <div class="box-body">
            <div class="table-responsive">
              <table id="purchaseList" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="10%">{{ trans('message.table.invoice') }} #</th>
                    
                    <th>{{ trans('message.table.ord_date') }}</th>
                    <th>{{ trans('message.invoice.total') }}</th>
                    <th width="5%" class="hideColumn">{{ trans('message.table.action') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($purchData as $data)
                  <tr>
                    <td><a href="{{URL::to('/')}}/purchase/view-purchase-details/{{$data->order_no}}" >{{ $data->reference }}</a></td>
                    <td>{{ formatDate($data->ord_date)}}</td>
                    <td>{{ Session::get('currency_symbol').number_format($data->total,2,'.',',') }}</td>
                    
                    <td class="hideColumn">
                    @if(!empty(Session::get('purchese_edit')))
                        <a  title="edit" class="btn btn-xs btn-primary" href='{{ url("purchase/edit/$data->order_no") }}'><span class="fa fa-edit"></span></a> &nbsp;
                    @endif
                    @if(!empty(Session::get('purchese_delete')))
                        <form method="POST" action="{{ url("purchase/delete/$data->order_no") }}" accept-charset="UTF-8" style="display:inline">
                            {!! csrf_field() !!}
                            <button title="delete" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_invoice_header') }}" data-message="{{ trans('message.table.delete_invoice') }}">
                                <i class="glyphicon glyphicon-trash"></i> 
                            </button>
                        </form>
                    @endif
                    </td>
                  </tr>
                 @endforeach
                  </tfoot>
                 </table>
            </div>

          </div>
        </div>

    </section>
@endsection
@section('js')
    <script type="text/javascript">
      $(function () {
        $("#purchaseList").DataTable({
          "order": [],
          "columnDefs": [ {
            "targets": 3,
            "orderable": false
            } ],

            "language": '{{Session::get('dflt_lang')}}',
            "pageLength": '{{Session::get('row_per_page')}}'
        });
      });
    </script>
@endsection