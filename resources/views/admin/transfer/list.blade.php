@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">

    <div class="box box-default">
      <div class="box-body">
        <div class="row">
          <div class="col-md-10">
           <div class="top-bar-title padding-bottom">{{ trans('message.sidebar.stock-move-list') }}</div>
          </div> 
          <div class="col-md-2">
             @if(!empty(Session::get('transfer_add')))
              <a href="{{ url('transfer/create') }}" class="btn btn-block btn-default btn-flat btn-border-orange"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.extra_text.new_transfer') }}</a>
              @endif
          </div>
        </div>
      </div>
    </div>


    <div class="row">
      <div class="col-md-12">
        <!-- Default box -->
        <div class="box">
          <!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table id="itemList" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="15%">{{ trans('message.table.transfer_no') }}</th>
                    <th>{{ trans('message.form.source') }}</th>
                    <th>{{ trans('message.form.destination') }}</th>
                    
                    <th>{{ trans('message.form.qty') }}</th>
                    <th>{{ trans('message.form.date') }}</th>
                    <th width="3%">{{ trans('message.table.action') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($list as $data)
                  <tr>
                    <td align="center"><a href="{{ url('transfer/view-details/'.$data->id) }}">{{sprintf("%04d", $data->id)}}</a></td>
                    <td>{{ getDestinatin($data->source) }}</td>
                    <td>{{ getDestinatin($data->destination) }}</td>
                    
                    <td>{{ $data->qty}}</td>
                    <td>{{ formatDate($data->transfer_date)}}</td>
                    <td>
                      <a title="edit" class="btn btn-xs btn-primary" href='{{ url("transfer/view-details/$data->id") }}'><span class="fa fa-eye"></span></a>
                      <form method="POST" action="{{ url("transfer/delete/$data->id") }}" accept-charset="UTF-8" style="display:inline">
                          {!! csrf_field() !!}
                          <button class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.extra_text.delete_transfer') }}" data-message="{{ trans('message.extra_text.delete_transfer_confirm') }}">
                             <i class="fa fa-remove" aria-hidden="true"></i>
                          </button>
                      </form>
                    </td>
                  </tr>
                 @endforeach
                  </tfoot>
                 </table>
            </div>
          </div>
              <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
    </div>
    
    </section>

@include('layouts.includes.message_boxes')
@endsection
@section('js')
<script type="text/javascript">
    $(function () {
    $("#itemList").DataTable({
    "order": [],
    "columnDefs": [ {
      "targets": 4,
      "orderable": false
      } ],

      "language": '{{Session::get('dflt_lang')}}',
      "pageLength": '{{Session::get('row_per_page')}}'
    });
    });
</script>
@endsection