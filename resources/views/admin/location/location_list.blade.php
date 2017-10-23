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
                 <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.locations') }}</div>
                </div> 
                <div class="col-md-3">
                  @if (!empty(Session::get('loc_add')))
                    <a href="{{ url('create-location') }}" class="btn btn-block btn-default btn-flat btn-border-orange"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.table.add_new_loc') }}</a>
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
                  <th>{{ trans('message.table.location_name') }}</th>
                  <th>{{ trans('message.table.location_code') }}</th>
                  <th>{{ trans('message.table.delivery_address') }}</th>
                  <th>{{ trans('message.form.default_loc') }}</th>
                  <th>{{ trans('message.table.phone') }}</th>
                  <th width="5%">{{ trans('message.table.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($locationData as $data)
                <tr>
                  <td><a href="{{ url("edit-location/$data->id") }}">{{ $data->location_name }}</a></td>
                  <td>{{ $data->loc_code }}</td>
                  <td>{{ $data->delivery_address }}</td>
                  
                  <td>{{ $data->inactive == 1 ? 'Yes':"No" }}</td>
                  <td>{{ $data->contact }}</td>
                  <td>
                  @if (!empty(Session::get('loc_edit')))
                      <a title="{{ trans('message.form.edit') }}" class="btn btn-xs btn-primary" href='{{ url("edit-location/$data->id") }}'><span class="fa fa-edit"></span></a> &nbsp;
                  @endif

                  @if (!empty(Session::get('loc_delete')))
                       @if(!in_array($data->id,[1]))
                      <form method="POST" action="{{ url("delete-location/$data->id") }}" accept-charset="UTF-8" style="display:inline">
                          {!! csrf_field() !!}
                          <button title="{{ trans('message.form.Delete') }}" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_location_header') }}" data-message="{{ trans('message.table.delete_location') }}">
                              <i class="glyphicon glyphicon-trash"></i> 
                          </button>
                      </form>
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
          "columnDefs": [ {
            "targets": 5,
            "orderable": false
            } ],

            "language": '{{Session::get('dflt_lang')}}',
            "pageLength": '{{Session::get('row_per_page')}}'
        });
        
      });
    </script>
@endsection