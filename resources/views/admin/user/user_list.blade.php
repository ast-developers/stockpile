@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
    
      <div class="row">
        <div class="col-md-3">
      @include('layouts.includes.company_menu')
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-default">
            <div class="box-body">
              <div class="row">
                <div class="col-md-9">
                 <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.team_member') }}</div>
                </div> 
                <div class="col-md-3">
                  @if (!empty(Session::get('user_add')))
                    <a href="{{ url('create-user') }}" class="btn btn-block btn-default btn-flat btn-border-orange"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.extra_text.add_new_user') }}</a>
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
                  <th>{{ trans('message.form.name') }}</th>
                  <th>{{ trans('message.table.email') }}</th>
                  <th>{{ trans('message.header.role') }}</th>
                  <th>{{ trans('message.table.phone') }}</th>
                  
                  <th width="15%">{{ trans('message.table.action') }}</th>
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
                  @if(!in_array($data->id,[1,2]))
                  @if (!empty(Session::get('user_edit')))
                      <a title="{{ trans('message.form.edit') }}" class="btn btn-xs btn-primary" href='{{ url("edit-user/$data->id") }}'><span class="fa fa-edit"></span></a> &nbsp;
                  @endif

                  @if (!empty(Session::get('user_delete')))
                    @if ($data->id != Auth::user()->id)
                      <form method="POST" action="{{ url("delete-user/$data->id") }}" accept-charset="UTF-8" style="display:inline">
                          {!! csrf_field() !!}
                          <button title="{{ trans('message.form.Delete') }}" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_user_header') }}" data-message="{{ trans('message.table.delete_user') }}">
                              <i class="glyphicon glyphicon-trash"></i> 
                          </button>
                      </form>
                    @endif
                  @endif
                  @endif
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