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
                 <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.user_role') }}</div>
                </div> 
                <div class="col-md-3">
                    <a href="{{ url('create-role') }}" class="btn btn-block btn-default btn-flat btn-border-orange"><span class="fa fa-plus"> &nbsp;</span>{{ trans('message.table.add_new_role') }}</a>
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
                  <th>{{ trans('message.table.role_name') }}</th>
                  <th>{{ trans('message.table.description') }}</th>
                  <th>{{ trans('message.table.sections') }}</th>
                  <th width="5%">{{ trans('message.table.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($roleData as $data)
                <?php
                  $section = unserialize($data->sections);
                  $areas = unserialize($data->areas);
                ?>
                <tr>
                  <td><a href="{{ ($data->id != 1) ? url("edit-role/$data->id") :'#'}}">{{ $data->role }}</a></td>
                  <td>{{ $data->description }}</td>
                  <td>
                    <?php
                        if (isset($section['category']) && $section['category']==100) {
                          echo "<span class='label label-default'>Category</span>";
                        }

                        if (isset($section['loc']) && $section['loc']==200) {
                          echo " "."<span class='label label-default'>Location</span>";
                        } 
                        if (isset($section['item']) && $section['item']==300) {
                          echo " "."<span class='label label-default'>Item</span>";
                        }
                        if (isset($section['user']) && $section['user']==400) {
                          echo " "."<span class='label label-default'>User</span>";
                        }
                        if (isset($section['role']) && $section['role']==500) {
                          echo " "."<span class='label label-default'>User Role</span>";
                        }
                        if (isset($section['unit']) && $section['unit']==600) {
                          echo " "."<span class='label label-default'>Unit</span>";
                        }

                        if (isset($section['customer']) && $section['customer']==700) {
                          echo " "."<span class='label label-default'>Customer</span>";
                        }

                        if (isset($section['sales']) && $section['sales']==800) {
                          echo " "."<span class='label label-default'>Sale</span>";
                        }
                        if (isset($section['purchese']) && $section['purchese']==900) {
                          echo " "."<span class='label label-default'>Purchase</span>";
                        }

                        if (isset($section['supplier']) && $section['supplier']==1000) {
                          echo " "."<span class='label label-default'>Supplier</span>";
                        }

                        if (isset($section['transfer']) && $section['transfer']==1100) {
                          echo " "."<span class='label label-default'>Transfer</span>";
                        }

                        if (isset($section['order']) && $section['order']==1200) {
                          echo " "."<span class='label label-default'>Order</span>";
                        }

                        if (isset($section['shipment']) && $section['shipment']==1300) {
                          echo " "."<span class='label label-default'>Shipment</span>";
                        }
                        if (isset($section['payment']) && $section['payment']==1400) {
                          echo " "."<span class='label label-default'>Payment</span>";
                        }
                         if (isset($section['backup']) && $section['backup']==1500) {
                          echo " "."<span class='label label-default'>Database backup</span>";
                        }
                        if (isset($section['email']) && $section['email']==1600) {
                          echo " "."<span class='label label-default'>Email Setup</span>";
                        }
                        if (isset($areas['emailtemp']) && $areas['emailtemp']==1700) {
                          echo " "."<span class='label label-default'>Email template</span>";
                        }


                        if (isset($areas['preference']) && $areas['preference']==1800) {
                          echo " "."<span class='label label-default'>Preference</span>";
                        }
                        if (isset($section['tax']) && $section['tax']==1900) {
                          echo " "."<span class='label label-default'>Tax</span>";
                        }
                        if (isset($section['salestype']) && $section['salestype']==2000) {
                          echo " "."<span class='label label-default'>Sales Type</span>";
                        }
                        if (isset($section['currencies']) && $section['currencies']==2100) {
                          echo " "."<span class='label label-default'>Currency</span>";
                        }
                       
                        if (isset($section['paymentterm']) && $section['paymentterm']==2200) {
                          echo " "."<span class='label label-default'>Payment terms</span>";
                        }
                        if (isset($section['paymentmethod']) && $section['paymentmethod']==2300) {
                          echo " "."<span class='label label-default'>Payment method</span>";
                        }
                        if (isset($areas['companysetting']) && $areas['companysetting']==2400) {
                          echo " "."<span class='label label-default'>Company setting</span>";
                        }
                    ?>
                  </td>
                  

                  <td>
                   @if ($data->id != 1)
                      <a title="{{ trans('message.form.edit') }}" class="btn btn-xs btn-primary" href='{{ url("edit-role/$data->id") }}'><span class="fa fa-edit"></span></a> &nbsp;
                  
                      <form method="POST" action="{{ url("delete-role/$data->id") }}" accept-charset="UTF-8" style="display:inline">
                          {!! csrf_field() !!}
                          <button title="{{ trans('message.form.Delete') }}" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_role_header') }}" data-message="{{ trans('message.table.delete_role') }}">
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
            "targets": 3,
            "orderable": false
            } ],

            "language": '{{Session::get('dflt_lang')}}',
            "pageLength": '{{Session::get('row_per_page')}}'
        });
        
      });
    </script>
@endsection