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
            <div class="col-md-6">
             <div class="top-bar-title padding-bottom">{{ trans('message.sidebar.item_category') }}</div>
            </div> 

           @if (!empty($access['cat_add']))
            <div class="col-md-3 top-left-btn">
                <a href="{{ URL::to('categoryimport') }}" class="btn btn-block btn-default btn-flat btn-border-purple"><span class="fa fa-upload"> &nbsp;</span>{{ trans('message.extra_text.import_new_categry') }}</a>
            </div>

            <div class="col-md-3 top-right-btn">
                <a href="javascript:void(0)" data-toggle="modal" data-target="#add-category" class="btn btn-block btn-default btn-flat btn-border-orange"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.table.add_new_category') }}</a>
            </div>
            @endif

          </div>
        </div>
      </div>

          <div class="box">
            <div class="box-header">
              <a href="{{ URL::to('categorydownloadExcel/csv') }}"><button class="btn btn-default btn-flat btn-border-info"><span class="fa fa-download"> &nbsp;</span>{{ trans('message.table.downolad_csv') }}</button></a>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>{{ trans('message.table.category') }}</th>
                  <th>{{ trans('message.table.unit') }}</th>
                  <th width="5%">{{ trans('message.table.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($categoryData as $data)
                <tr>
                  <td><a class="edit_category" id="{{$data->category_id}}" href="javascript:void(0)">{{ $data->description }}</a></td>
                  <td>{{ $data->name }}</td>
                  <td>
                  @if (!empty($access['cat_edit']) && !in_array($data->category_id,[1]))

                      <a title="{{ trans('message.form.edit') }}" href="javascript:void(0)" class="btn btn-xs btn-primary edit_category" id="{{$data->category_id}}"><span class="fa fa-edit"></span></a> &nbsp;
                  @endif

                  @if (!empty($access['cat_delete']))
                    @if(!in_array($data->category_id,[1]))
                      <form method="POST" action="{{ url("delete-category/$data->category_id") }}" accept-charset="UTF-8" style="display:inline">
                          {!! csrf_field() !!}
                          <button title="{{ trans('message.form.Delete') }}" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_category_header') }}" data-message="{{ trans('message.table.delete_category') }}">
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
            <!-- /.box-body -->
          </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>

<div id="add-category" class="modal fade" role="dialog" style="display: none;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">{{ trans('message.table.add_new') }}</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('save-category') }}" method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
          
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.category') }}</label>

            <div class="col-sm-6">
              <input type="text" placeholder="{{ trans('message.form.category') }}" class="form-control" name="description">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.unit') }}</label>

            <div class="col-sm-6">
              <select class="form-control valdation_select" name="dflt_units" >
              <option value="">{{ trans('message.form.select_one') }}</option>
              @foreach ($unitData as $data)
                <option value="{{$data->id}}" >{{$data->name}}</option>
              @endforeach
              </select>
            </div>
          </div>

          
          <div class="form-group">
            <label for="btn_save" class="col-sm-3 control-label"></label>
            <div class="col-sm-6">
              <button type="button" class="btn btn-info btn-flat" data-dismiss="modal">{{ trans('message.form.close') }}</button>
              <button type="submit" class="btn btn-primary btn-flat">{{ trans('message.form.submit') }}</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>


<div id="edit-category" class="modal fade" role="dialog" style="display: none;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">{{ trans('message.table.add_new') }}</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('update-category') }}" method="post" id="editCat" class="form-horizontal">
            {!! csrf_field() !!}
          
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.category') }}</label>

            <div class="col-sm-6">
              <input type="text" placeholder="Name" class="form-control" id="name" name="description">
              <span id="val_name" style="color: red"></span>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.form.unit') }}</label>

            <div class="col-sm-6">
              <select class="form-control" name="dflt_units" id="dflt_units">
              
              @foreach ($unitData as $data)
                <option value="{{$data->id}}" >{{$data->name}}</option>
              @endforeach
              </select>
            </div>
          </div>
          <input type="hidden" name="cat_id" id="cat_id">

          @if (!empty($access['cat_edit']))
          <div class="form-group">
            <label for="btn_save" class="col-sm-3 control-label"></label>
            <div class="col-sm-6">
              <button type="button" class="btn btn-info btn-flat" data-dismiss="modal">{{ trans('message.form.close') }}</button>
              <button type="submit" class="btn btn-primary btn-flat">{{ trans('message.form.submit') }}</button>
            </div>
          </div>
          @endif
          
        </form>
      </div>
    </div>

  </div>
</div>
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
            //"pageLength": 5
        });
        
      });


      $('.edit_category').on('click', function() {
        var id = $(this).attr("id");

        $.ajax({
            url: '{{ URL::to('edit-category')}}',
            data:{  // data that will be sent
                id:id
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
              
                $('#name').val(data.name);
                $('#dflt_units').val(data.dflt_units);
                $('#cat_id').val(data.category_id);

                $('#edit-category').modal();
            }
        });

    });

      $('#myform1').validate({
        rules: {
            description: {
                required: true
            },
            dflt_units: {
                required: true
            }                     
        }
    });

    $('#editCat').validate({
        rules: {
            description: {
                required: true
            },
            dflt_units: {
                required: true
            }                     
        }
    });
    </script>
@endsection