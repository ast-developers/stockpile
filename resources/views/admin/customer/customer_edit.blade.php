@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
        <div class="box">
           <div class="panel-body">
                <ul class="nav nav-tabs cus" role="tablist">
                    <li class="active">
                      <a href='{{url("customer/edit/$customerData->debtor_no")}}' >{{ trans('message.sidebar.profile') }}</a>
                    </li>
                    <li>
                      <a href="{{url("customer/order/$customerData->debtor_no")}}" >{{ trans('message.extra_text.sales_orders') }}</a>
                    </li>
                    <li>
                      <a href="{{url("customer/invoice/$customerData->debtor_no")}}" >{{ trans('message.extra_text.invoices') }}</a>
                    </li>
                    <li>
                      <a href="{{url("customer/payment/$customerData->debtor_no")}}" >{{ trans('message.extra_text.payments') }}</a>
                    </li>
                    <li>
                      <a href="{{url("customer/shipment/$customerData->debtor_no")}}" >{{ trans('message.extra_text.deliveries') }}</a>
                    </li>
               </ul>
              <div class="clearfix"></div>
           </div>
        </div>

        <h3>{{$customerData->name}}</h3>
        <div class="box">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="tabs" style="font-size:12px">
              <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">{{ trans('message.table.general_settings') }}</a></li>
              <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">{{ trans('message.invoice.branch') }}</a></li>
              @if(!empty($customerData->password))
              <li><a href="#tab_3" data-toggle="tab" aria-expanded="false">{{ trans('message.form.update_password') }}</a></li>
              @else
              <li><a href="#tab_3" data-toggle="tab" aria-expanded="false">{{ trans('message.form.set_password') }}</a></li>
              @endif
            </ul>
            <div class="tab-content">
              <div class="tab-pane fade in active" id="tab_1">
                <div class="row">
                <div class="col-md-8">
                 
                <!-- form start -->
                <form action="{{ url("update-customer/$customerData->debtor_no") }}" method="post" id="myform1" class="form-horizontal">
                      
                  <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                        <div class="box-body">
                          
                          <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputEmail3">{{ trans('message.form.name') }}</label>

                            <div class="col-sm-7">
                              <input type="text" placeholder="{{ trans('message.form.full_name') }}" class="form-control valdation_check" id="fname" name="name" value="{{$customerData->name}}">
                              <span id="val_fname" style="color: red"></span>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputEmail3">{{ trans('message.form.email') }}</label>

                            <div class="col-sm-7">
                              <input type="text" placeholder="{{ trans('message.table.email') }}" class="form-control valdation_check" id="email" name="email" value="{{$customerData->email}}" readonly>
                              <span id="val_email" style="color: red"></span>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputEmail3">{{ trans('message.table.phone') }}</label>

                            <div class="col-sm-7">
                              <input type="text" placeholder="{{ trans('message.table.phone') }}" class="form-control valdation_check" id="name" name="phone" value="{{$customerData->phone}}">
                              <span id="val_name" style="color: red"></span>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputEmail3">{{ trans('message.form.status') }}</label>

                            <div class="col-sm-7">
                              <select class="form-control valdation_select" name="inactive" >
                                
                                <option value="0" <?=isset($customerData->inactive) && $customerData->inactive ==  0? 'selected':""?> >Active</option>
                                <option value="1"  <?=isset($customerData->inactive) && $customerData->inactive == 1 ? 'selected':""?> >Inactive</option>
                              
                              </select>
                              @if(!empty(Session::get('customer_edit')))
                                <div style="margin-top:10px">
                                  <a href="{{ url('customer/list') }}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                                  <button class="btn btn-primary pull-right btn-flat" type="submit">{{ trans('message.form.submit') }}</button>
                                </div>
                               @endif
                            </div>
                          </div>
                        </div>
                      </form>
              </div>
              </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">

              <div class="row">
                <div class="col-md-12">
                  @if(!empty(Session::get('customer_edit')))
				  
                  <br>
                  <button data-toggle="modal" data-target="#add-brunch" type="button" class="btn btn-default btn-flat btn-border-orange add_br">{{ trans('message.extra_text.add_new_branch') }}</button>
                  <br>
                  <br>
				  <br>
                  @endif
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>{{ trans('message.invoice.branch') }}</th>
                      
                      <th>{{ trans('message.extra_text.billing_address') }}</th>
                      <th>{{ trans('message.extra_text.shipping_address') }}</th>
                      
                      <th width="15%" class="text-center">{{ trans('message.table.action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                  @if(!empty($cusBranchData))
                  
                  @foreach($cusBranchData as $data)
                    <tr>
                      <td><a href="#" id="{{$data->branch_code}}" class="edit_br">{{ $data->br_name }}</a></td>
                     
                      <td>{{ $data->billing_street.', '.$data->billing_city.', '.$data->billing_state }}</td>
                      <td>{{ $data->shipping_street.', '.$data->shipping_city.', '.$data->shipping_state }}</td>                      

                      <td class="text-center">
                        @if(!empty(Session::get('customer_edit')))
                          <button class="btn btn-xs btn-primary edit_br" id="{{$data->branch_code}}" type="button">
                              <i class="glyphicon glyphicon-edit"></i> 
                          </button>&nbsp;

                          <form method="POST" action="{{ url("delete-branch/$data->branch_code") }}" accept-charset="UTF-8" style="display:inline">
                              {!! csrf_field() !!}
                              <button class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.table.delete_branch_header') }}" data-message="{{ trans('message.table.delete_branch') }}">
                                  <i class="glyphicon glyphicon-trash"></i> 
                              </button>
                          </form>
                          @endif
                      </td>
                    </tr>
                   @endforeach
                   @endif
                    </tfoot>
                  </table>

                </div>
              </div>

              </div>
              <!-- /.tab-pane -->
              <!-- /.tab-pane -->
        
              <div class="tab-pane" id="tab_3">

                    <div class="row">
                      <div class="col-md-6">
                          <form action='{{url("customer/update-password")}}' class="form-horizontal" id="password-form" method="POST">
                            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                            <input type="hidden" value="{{$customerData->debtor_no}}" name="customer_id">
                            <div class="form-group">
                              <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.form.password') }}</label>

                              <div class="col-sm-8">
                              <input type="password" class="form-control" name="password" id="password">
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.form.confirm_password') }}</label>

                              <div class="col-sm-8">
                              <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                              <br>
                              @if(!empty(Session::get('customer_edit')))
                              <button class="btn btn-primary pull-right btn-flat" type="submit">{{ trans('message.form.submit') }}</button>
                              @endif
                              </div>
                            </div>
                          </form>
                      </div>

              </div>

              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          
        </div>
        
        <!-- /.box-footer-->
      
      <!-- /edit branch .box -->
    <div id="edit-brunch" class="modal fade" role="dialog" style="display: none;">
        
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">{{ trans('message.table.branch_details') }}</h4>
              </div>
              <div class="modal-body">
                <form class="form-horizontal" id="updateBranch">
                {!! csrf_field() !!}
                  
                  <div class="form-group">
                    <label for="quantity" class="col-sm-4 control-label require">{{ trans('message.table.branch_name') }}</label>
                    <div class="col-sm-6">
                      <input type="text" name="br_name" class="form-control" id="br_name" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="quantity" class="col-sm-4 control-label">{{ trans('message.table.contact') }}</label>
                    <div class="col-sm-6">
                      <input type="text" name="br_contact" class="form-control" id="br_contact" >
                    </div>
                  </div>
                  
                  
                  <h4 class="text-info text-center">{{ trans('message.invoice.billing_address') }}</h4>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.street') }}</label>

                            <div class="col-sm-6">
                              <input name="bill_street" id="bill_street" type="text" class="form-control">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.city') }}</label>

                            <div class="col-sm-6">
                              <input name="bill_city" id="bill_city" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.state') }}</label>

                            <div class="col-sm-6">
                              <input name="bill_state" id="bill_state" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.zipcode') }}</label>

                            <div class="col-sm-6">
                              <input name="bill_zipCode" id="bill_zipCode" type="text" class="form-control" name="bill_zipCode">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.country') }}</label>
                            <div class="col-sm-6">
                              <select class="form-control" name="billing_country_id" id="billing_country_id">
                              <option value="">{{ trans('message.form.select_one') }}</option>
                              @foreach ($countries as $data)
                                <option value="{{$data->code}}" >{{$data->country}}</option>
                              @endforeach
                              </select>
                            </div>
                          </div>


                          <h4 class="text-info text-center">{{ trans('message.invoice.shipping_address') }}<button id="copy" class="btn btn-default btn-xs" type="button">{{ trans('message.table.copy_address') }}</button></h4>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.street') }}</label>

                            <div class="col-sm-6">
                              <input name="ship_street" id="ship_street" type="text" class="form-control">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.city') }}</label>

                            <div class="col-sm-6">
                              <input name="ship_city" id="ship_city" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.state') }}</label>

                            <div class="col-sm-6">
                              <input name="ship_city" id="ship_state" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.zipcode') }}</label>

                            <div class="col-sm-6">
                              <input name="ship_zipCode" id="ship_zipCode" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.country') }}</label>
                            <div class="col-sm-6">
                              <select class="form-control" name="shipping_country_id" id="shipping_country_id">
                              <option value="">{{ trans('message.form.select_one') }}</option>
                              @foreach ($countries as $data)
                                <option value="{{$data->code}}" >{{$data->country}}</option>
                              @endforeach
                              </select>
                            </div>
                          </div>

                  <input type="hidden" name="br_id" id="br_id">
                  <div class="form-group">
                    <label for="btn_save" class="col-sm-4 control-label"></label>
                    <div class="col-sm-6">
                      <button type="button" class="btn btn-info btn-flat" data-dismiss="modal">{{ trans('message.form.close') }}</button>
                      <button type="submit" class="btn btn-primary btn-flat pull-right">{{ trans('message.form.update') }}</button>
                      
                    </div>
                  </div>
                </form>
              </div>
            </div>

          </div>
        </div>
  
  <!-- /.add New Branch box -->
        <div id="add-brunch" class="modal fade" role="dialog" style="display: none;">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">{{ trans('message.table.branch_details') }}</h4>
              </div>
              <div class="modal-body">
                <form class="form-horizontal" id="addBranch">
                  
                  <div class="form-group">
                    <label for="quantity" class="col-sm-4 control-label require">{{ trans('message.table.branch_name') }}</label>
                    <div class="col-sm-6">
                      <input type="text" name="br_name" class="form-control" id="add_br_name" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="quantity" class="col-sm-4 control-label require">{{ trans('message.table.contact') }}</label>
                    <div class="col-sm-6">
                      <input type="text" name="br_contact" class="form-control" id="add_br_contact" >
                    </div>
                  </div>
                  
                  <h4 class="text-info text-center">{{ trans('message.invoice.billing_address') }}</h4>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.street') }}</label>

                            <div class="col-sm-6">
                              <input name="bill_street" id="add_bill_street" type="text" class="form-control">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.city') }}</label>

                            <div class="col-sm-6">
                              <input name="bill_city" id="add_bill_city" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.state') }}</label>

                            <div class="col-sm-6">
                              <input name="bill_state" id="add_bill_state" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.zipcode') }}</label>

                            <div class="col-sm-6">
                              <input name="bill_zipCode" id="add_bill_zipCode" type="text" class="form-control" name="bill_zipCode">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.country') }}</label>
                            <div class="col-sm-6">
                              <select class="form-control select2" name="bill_country_id" id="bill_country_id">
                              <option value="">{{ trans('message.form.select_one') }}</option>
                              @foreach ($countries as $data)
                                <option value="{{$data->code}}" >{{$data->country}}</option>
                              @endforeach
                              </select>
                            </div>
                          </div>

                          <h4 class="text-info text-center">{{ trans('message.invoice.shipping_address') }} <button id="copyAddress" class="btn btn-default btn-xs" type="button">Copy Address</button></h4>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.street') }}</label>

                            <div class="col-sm-6">
                              <input name="ship_street" id="add_ship_street" type="text" class="form-control">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.city') }}</label>

                            <div class="col-sm-6">
                              <input name="ship_city" id="add_ship_city" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.state') }}</label>

                            <div class="col-sm-6">
                              <input name="ship_city" id="add_ship_state" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputEmail3">{{ trans('message.invoice.zipcode') }}</label>

                            <div class="col-sm-6">
                              <input name="ship_zipCode" id="add_ship_zipCode" type="text" class="form-control">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">{{ trans('message.invoice.country') }}</label>

                            <div class="col-sm-6">
                              <select class="form-control select2" name="ship_country_id" id="ship_country_id">
                              <option value="">{{ trans('message.form.select_one') }}</option>
                              @foreach ($countries as $data)
                                <option value="{{$data->code}}" >{{$data->country}}</option>
                              @endforeach
                              </select>
                            </div>
                          </div>
                  <input type="hidden" name="cus_id" value="{{$customerData->debtor_no}}" id="cus_id">
                  <div class="form-group">
                    <label for="btn_save" class="col-sm-4 control-label"></label>
                    <div class="col-sm-6">
                      
                      <button type="button" class="btn btn-info btn-flat" data-dismiss="modal">{{ trans('message.form.close') }}</button>
                    <button type="submit" class="btn btn-primary btn-flat pull-right">{{ trans('message.form.submit') }}</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>

          </div>
        </div>
    @include('layouts.includes.message_boxes')
    </section>
@endsection


@section('js')
    <script type="text/javascript">

    $(function () {
      $("#example1").DataTable({
        "order": [],
        "columnDefs": [{
          "targets": 3,
          "orderable": false
          } ],

          "language": '{{Session::get('language')}}'
      });

        var type = window.location.hash.substr(1);
        console.log(type);

    });


    $('.edit_br').on('click', function() {
        var id = $(this).attr("id");

        $.ajax({
            url: '{{ URL::to('edit-branch')}}',
            data:{                  // data that will be sent
                id:id
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
              console.log(data);
                $('#br_name').val(data.br_name);
                $('#br_contact').val(data.br_contact);
                //$('#br_address').val(data.br_address);

                $('#bill_street').val(data.billing_street);
                $('#bill_city').val(data.billing_city);
                $('#bill_state').val(data.billing_state);
                $('#bill_zipCode').val(data.billing_zip_code);
                $('#billing_country_id').val(data.billing_country_id);
                
                $('#ship_street').val(data.shipping_street);
                $('#ship_city').val(data.shipping_city);
                $('#ship_state').val(data.shipping_state);
                $('#ship_zipCode').val(data.shipping_zip_code);
                $('#shipping_country_id').val(data.shipping_country_id);
                $('#br_id').val(data.br_id);

                $('#edit-brunch').modal();
            }
        });

    });

    $('#updateBranch').submit(function (e) {
        e.preventDefault();

        var br_name = $("#br_name").val();
        var br_contact = $("#br_contact").val();
        //var br_address = $("#br_address").val();

        var bill_street = $("#bill_street").val();
        var bill_city = $("#bill_city").val();
        var bill_state = $("#bill_state").val();
        var bill_zipCode = $("#bill_zipCode").val();
        var billing_country_id = $("#billing_country_id").val();

        var ship_street = $("#ship_street").val();
        var ship_city = $("#ship_city").val();
        var ship_state = $("#ship_state").val();
        var ship_zipCode = $("#ship_zipCode").val();
        var shipping_country_id = $("#shipping_country_id").val();

        var br_id = $("#br_id").val();
        
        $.ajax({
            url: '{{ URL::to('update-branch')}}',
            data:{                // data that will be sent
                
                br_name:br_name,
                br_contact:br_contact,
                //br_address:br_address,
                bill_street:bill_street,
                bill_city:bill_city,
                bill_state:bill_state,
                bill_zipCode:bill_zipCode,
                billing_country_id:billing_country_id,
                ship_street:ship_street,
                ship_city:ship_city,
                ship_state:ship_state,
                ship_zipCode:ship_zipCode,
                shipping_country_id:shipping_country_id,
                br_id:br_id
            },
            
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
              if(data.success == 1) {
                  
                  $('#edit-brunch').modal('hide');
                  location.reload();
              }
            }
        });

    });

    $('#addBranch').validate({
        rules: {
            br_name: {
                required: true
            },

            bill_street:{
               required: true
            },
            bill_city:{
               required: true
            },
            bill_state:{
               required: true
            },
            bill_country_id:{
               required: true
            },
            bill_zipCode:{
               required: true
            }                      
        }
    });

    $('#password-form').validate({
        rules: {
            password: {
                required: true,
                minlength: 5
            },
            password_confirmation: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            }
        }
    });


    $('#addBranch').submit(function (e) {
        e.preventDefault();

        var br_name = $("#add_br_name").val();
        var br_contact = $("#add_br_contact").val();
       // var br_address = $("#add_br_address").val();

        var bill_street = $("#add_bill_street").val();
        var bill_city = $("#add_bill_city").val();
        var bill_state = $("#add_bill_state").val();
        var bill_zipCode = $("#add_bill_zipCode").val();
        var bill_country_id = $("#bill_country_id").val();

        var ship_street = $("#add_ship_street").val();
        var ship_city = $("#add_ship_city").val();
        var ship_state = $("#add_ship_state").val();
        var ship_zipCode = $("#add_ship_zipCode").val();
        var ship_country_id = $("#ship_country_id").val();
        var cus_id = $("#cus_id").val();
        
        if(br_name && br_contact && bill_street && bill_city && bill_zipCode) {

        $.ajax({
            url: '{{ URL::to('save-branch')}}',
            data:{                  // data that will be sent
                
                br_name:br_name,
                br_contact:br_contact,
               // br_address:br_address,
                bill_street:bill_street,
                bill_city:bill_city,
                bill_state:bill_state,
                bill_zipCode:bill_zipCode,
                bill_country_id:bill_country_id,
                ship_street:ship_street,
                ship_city:ship_city,
                ship_state:ship_state,
                ship_zipCode:ship_zipCode,
                ship_country_id:ship_country_id,
                cus_id:cus_id
            },
            
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
              if(data.success == 1) {
                  
                $('#add-brunch').modal('hide');
                location.reload();

              }
            }
        });
      }

    });

    $('#copy').on('click', function() {
        $('#ship_street').val($('#bill_street').val());
        $('#ship_city').val($('#bill_city').val());
        $('#ship_state').val($('#bill_state').val());
        $('#ship_zipCode').val($('#bill_zipCode').val());
      
        $("#shipping_country_id").val($('#billing_country_id').val());
    })

    $('#copyAddress').on('click', function() {
        $('#add_ship_street').val($('#add_bill_street').val());
        $('#add_ship_city').val($('#add_bill_city').val());
        $('#add_ship_state').val($('#add_bill_state').val());
        $('#add_ship_zipCode').val($('#add_bill_zipCode').val());
      
        $("#ship_country_id").val($('#bill_country_id').val());
    })

jQuery(function($) {
    var index = 'qpsstats-active-tab';
    //  Define friendly data store name
    var dataStore = window.sessionStorage;
    var oldIndex = 0;
    //  Start magic!
    try {
        // getter: Fetch previous value
        oldIndex = dataStore.getItem(index);
    } catch(e) {}
 
    $( "#tabs" ).tabs({        active: oldIndex,
        activate: function(event, ui) {
            //  Get future value
            var newIndex = ui.newTab.parent().children().index(ui.newTab);
            //  Set future value
            try {
                dataStore.setItem( index, newIndex );
            } catch(e) {}
        }
    });
});


    </script>
@endsection