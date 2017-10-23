@extends('layouts.customer_panel')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <h4>{{ trans('message.customer_panel.my_branch')}}</h4>
            </div>
        </div>
      <!-- Default box -->
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      
                      <th>{{ trans('message.customer_panel.branch_name')}}</th>
                      <th>{{ trans('message.customer_panel.contact')}}</th>
                      <th width="15%" class="text-center">{{ trans('message.customer_panel.action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                  @if(!empty($cusBranchData))
                  
                  @foreach($cusBranchData as $data)
                    <tr>
                      <td>{{ $data->br_name }}</td>
                      <td>{{ $data->br_contact }}</td>
                      
                      <td class="text-center">

                        <a class="btn btn-xs btn-info" href='{{ url("customer-panel/branch/edit/$data->branch_code") }}'><span class="fa fa-edit"></span></a>
                      </td>
                    </tr>
                   @endforeach
                   @endif
                    </tfoot>
                  </table>
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
      
      $("#example1").DataTable({
        "order": []
      });
      
    });

    </script>
@endsection