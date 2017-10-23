@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!--Default box -->
        @include('layouts.includes.user_menu')
        <h3>{{$user->real_name}}</h3> 
        
         <div class="box">
            <!-- /.box-header -->
            <div class="box-body">       
          <div class="col-md-12 col-xs-12">
              <div class="row">
            <form class="form-horizontal" action='{{ url("user/user-transfer-list/$user_id") }}' method="GET" id='salesHistoryReport'>
              
              <div class="col-md-2">
                  <label for="exampleInputEmail1">{{ trans('message.report.from') }}</label>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input class="form-control" id="from" type="text" name="from" value="<?= isset($from) ? $from : '' ?>" required>
                  </div>
              </div>
              
              <div class="col-md-2">
                  <label for="exampleInputEmail1">{{ trans('message.report.to') }}</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control" id="to" type="text" name="to" value="<?= isset($to) ? $to : '' ?>" required>
                  </div>
              </div>

              <div class="col-md-2">
                <label for="exampleInputEmail1">{{ trans('message.form.source') }}</label>
                <select class="form-control select2" name="source" id="source" required>
                <option value="all" <?= ($source=='all') ? 'selected' : ''?>>All</option>
                @foreach($locationList as $data)
                  <option value="{{$data->loc_code}}" <?= ($data->loc_code == $source) ? 'selected' : ''?>>{{$data->location_name}}</option>
                @endforeach
                </select>
              </div>

              <div class="col-md-2">
                <label for="exampleInputEmail1">{{ trans('message.form.destination') }}</label>
                <select class="form-control select2" name="destination" id="destination" required>
                <option value="all" <?= ($destination=='all') ? 'selected' : ''?>>All</option>
                @foreach($locationList as $data)
                  <option value="{{$data->loc_code}}" <?= ($data->loc_code == $destination) ? 'selected' : ''?>>{{$data->location_name}}</option>
                @endforeach
                </select>
              </div>

              <div class="col-md-1">
                <label for="btn">&nbsp;</label>
                <button type="submit" name="btn" class="btn btn-primary btn-flat">{{ trans('message.extra_text.filter') }}</button>
              </div>
            </form>
          </div>
          </div>
          </div>
      </div> 

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
        <!-- /.box-footer-->
    @include('layouts.includes.message_boxes')
    </section>
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

  $(".select2").select2({});
    
    $('#from').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: '{{Session::get('date_format_type')}}'
    });
   

    $('#to').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: '{{Session::get('date_format_type')}}'
    });


    </script>
@endsection