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
                 <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.db_backup') }}</div>
                </div> 
                <div class="col-md-3">
                  @if (!empty(Session::get('backup_add')))
                    <a href="{{url('back-up')}}" class="btn btn-block btn-default btn-flat btn-border-orange" id="backupid"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.extra_text.new_backup') }}</a>
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
                  <th>#</th>
                  <th>{{ trans('message.table.name') }}</th>
                  <th>{{ trans('message.table.date') }}</th>
                  <th width="5%">{{ trans('message.table.action') }}</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 0;?>
                @foreach ($backupData as $data)
                <tr>
                  <td>{{ ++$i }}</td>
                  <td>{{ $data->name }}</td>
                  <td>{{ $data->created_at }}</td>
                  <td>
              
              @if (!empty(Session::get('backup_download')))
                      <a  title="Download" href="{{URL::to('/storage/laravel-backups/'.$data->name)}}"  class="btn btn-xs btn-danger edit_unit" id="{{$data->id}}" ><span class="fa fa-download"></span></a> &nbsp;
              @endif
            
                  </td>
                </tr>
               @endforeach
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.nav-tabs-custom -->
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
          "order": [],
          "columnDefs": [ {
            "targets": 3,
            "orderable": false
            } ],
            
            "language": '{{Session::get('dflt_lang')}}',
            "pageLength": '{{Session::get('row_per_page')}}'
        });
        

        $("#backupids").on('click', function(){
          alert("This option is not available on demo version");
          return false;
        });

      });
    </script>
@endsection