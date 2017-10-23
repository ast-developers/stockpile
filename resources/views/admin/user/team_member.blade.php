@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
    
      <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
          <div class="box box-default">
            <div class="box-body">
              <div class="row">
                <div class="col-md-9">
                 <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.team_member') }}</div>
                </div> 
              </div>
            </div>
          </div>

          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>{{ trans('message.form.name') }}</th>
                  <th>{{ trans('message.table.email') }}</th>
                  <th>{{ trans('message.header.role') }}</th>
                  <th>{{ trans('message.table.phone') }}</th>
                  
                  <th width="1%">{{ trans('message.table.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($userData as $data)
                <tr>
                  <td>{{ $data->real_name }}</td>
                  <td>{{ $data->email }}</td>
                  <td>{{ $role_name[$data->role_id] }}</td>
                  <td>{{ $data->phone }}</td>
                  <td>
                  <a class="btn btn-xs btn-info" href='{{ url("user/purchase-list/$data->id") }}'><span class="fa fa-eye"></span></a> &nbsp;
                  </td>
                </tr>
               @endforeach
                </tfoot>
              </table>
            </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      </div>

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

        "language": '{{Session::get('dflt_lang')}}',
        "pageLength": '{{Session::get('row_per_page')}}'
    });
    
  });

    </script>
@endsection