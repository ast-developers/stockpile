@extends('layouts.app')


@section('content')

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">
          @include('layouts.includes.sub_menu')
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-default">
            <div class="box-body">
              <div class="row">
                <div class="col-md-9">
                 <div class="top-bar-title padding-bottom">{{ trans('message.table.company') }}</div>
                </div> 
                <div class="col-md-3">
                  @if(!empty(Session::get('unit_add')))
                    <a href="{{ url('create-company') }}" class="btn btn-block btn-default btn-flat btn-border-orange">{{ trans('message.extra_text.new_company') }}</a>
                  @endif
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
                  <th>{{ trans('message.table.company') }}</th>
                  <th>{{ trans('message.table.db_host') }}</th>
                  <th>{{ trans('message.table.db_user') }}</th>
                  <th>{{ trans('message.table.db_name') }}</th>
                  <th>{{ trans('message.table.default') }}</th>
                  <th width="5%">{{ trans('message.table.action') }}</th>
                </tr>
                </thead>
                <tbody>
                
                @foreach($companyData as $data)
                <tr>
                  <td><a href="{{ url("edit-company/$data->company_id") }}">{{ $data->name }}</a></td>
                  <td>{{ $data->host }}</td>
                  <td>{{ $data->db_user }}</td>
                  <td>{{ $data->db_name }}</td>
                  <td>{{ $data->default }}</td>
                  <td>

                      <a title="{{ trans('message.form.edit') }}" class="btn btn-xs btn-primary" href='{{ url("edit-company/$data->company_id") }}'><span class="fa fa-edit"></span></a> &nbsp;
                  @if ($data->default == 'No')
                      <form method="POST" action="{{ url("delete-company/$data->company_id") }}" accept-charset="UTF-8" style="display:inline">
                          {!! csrf_field() !!}
                          <button title="{{ trans('message.form.Delete') }}" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_company_header') }}" data-message="{{ trans('message.table.delete_company') }}">
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
            <!-- /.box-body -->
          </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    @include('layouts.includes.message_boxes')
@endsection

@section('js')
    <script type="text/javascript">
     $(function () {
    $("#example1").DataTable({
      "columnDefs": [ {
        "targets": 2,
        "orderable": false
        } ],

        "language": '{{Session::get('dflt_lang')}}',
            "pageLength": '{{Session::get('row_per_page')}}'
    });
    
  });
    </script>
@endsection