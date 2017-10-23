@extends('layouts.app')
@section('content')

    <!-- Main content -->
    <section class="content">
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-8">
             <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.supplier') }}</div>
            </div>

            <div class="col-md-2 top-left-btn">
               @if(!empty(Session::get('supplier_add')))
                <a href="{{ URL::to('supplierimport') }}" class="btn btn-block btn-default btn-flat btn-border-purple"><span class="fa fa-upload"> &nbsp;</span>{{ trans('message.extra_text.import_new_supplier') }}</a>
                @endif
            </div>

            <div class="col-md-2 top-right-btn">
               @if(!empty(Session::get('supplier_add')))
                <a href="{{ url('create-supplier') }}" class="btn btn-block btn-default btn-flat btn-border-orange"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.table.add_new_supplier') }}</a>
                @endif
            </div>

          </div>
        </div>
      </div>

    <!-- Top Box-->
      <div class="box">
        <div class="box-body">
          <div class="col-md-2 col-xs-6 border-right">
              <h3 class="bold">{{$supplierCount}}</h3>
              <span class="bold">{{ trans('message.extra_text.total_supplier') }}</span>
          </div>
          <div class="col-md-2 col-xs-6 border-right">
              <h3 class="bold">{{$supplierActive}}</h3>
              <span>{{ trans('message.extra_text.active_supplier') }}</span>
          </div>
          <div class="col-md-2 col-xs-6">
              <h3 class="bold">{{$supplierInActive}}</h3>
              <span>{{ trans('message.extra_text.inactive_supplier') }}</span>
          </div>
        </div>
        <br>
      </div><!--Top Box End-->

      <!-- Default box -->
      <div class="box">
      @if(!empty(Session::get('supplier_add')))
            <div class="box-header">
              <a href="{{ URL::to('supplierdownloadCsv/csv') }}"><button class="btn btn-default btn-flat btn-border-info"> <span class="fa fa-download"></span> {{ trans('message.table.download_csv') }}</button></a>
            </div>
      @endif
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>{{ trans('message.table.supp_name') }}</th>
                  <th>{{ trans('message.form.email') }}</th>
                  <th>{{ trans('message.table.phone') }}</th>
                  <th>{{ trans('message.extra_text.address') }}</th>
                  <th>{{ trans('message.form.status') }}</th>
                  
                </tr>
                </thead>
                <tbody>
                @foreach($supplierData as $data)
                <tr>
                  <td><a href='{{ url("edit-supplier/$data->supplier_id") }}'>{{ $data->supp_name }}</a></td>
                  <td>{{ $data->email }}</td>
                  <td>{{ $data->contact }}</td>
                  <td>{{ !empty($data->address) ? $data->address :'' }}{{ !empty($data->city) ? ', '.$data->city:'' }}{{ !empty($data->state) ? ', '.$data->state:'' }}{{ !empty($data->country) ? ', '.$data->country:'' }}{{ !empty($data->zipcode) ? ', '.$data->zipcode:'' }}</td>
                  <td>
                  @if($data->inactive == 0)
                    <span class='label label-success'>{{ trans('message.table.active') }}</span>
                  @else
                    <span class='label label-danger'>{{ trans('message.table.inactive') }}</span>
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
    $("#example1").DataTable({
      "order": [],
      "columnDefs": [ {
        "targets": 4,
        "orderable": false
        } ],

        "language": '{{Session::get('language')}}'
    });
    
  });

    </script>
@endsection