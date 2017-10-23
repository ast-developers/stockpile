@extends('layouts.customer_panel')


@section('content')

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
        
        <div class="box">
      
            <!-- /.box-header -->
            <div class="box-body">
            <div class="col-md-9">
            <h3>Branch details</h3>
              <form action='{{url("customer-panel/branch/update/$branchData->branch_code")}}' method="POST" class="form-horizontal" id="addBranch">
                {!! csrf_field() !!}
                  <div class="form-group">
                    <label for="quantity" class="col-sm-4 control-label require">Branch Name</label>
                    <div class="col-sm-6">
                      <input type="text" value="{{$branchData->br_name}}" name="br_name" class="form-control" id="add_br_name" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="quantity" class="col-sm-4 control-label require">Contact</label>
                    <div class="col-sm-6">
                      <input type="text" value="{{$branchData->br_contact}}" name="br_contact" class="form-control" id="add_br_contact" >
                    </div>
                  </div>
                  
                  <h4 class="text-info text-center">Billing Address</h4>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">Street</label>

                            <div class="col-sm-6">
                              <input name="bill_street"  value="{{$branchData->billing_street}}" id="bill_street" type="text" class="form-control">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">City</label>

                            <div class="col-sm-6">
                              <input name="bill_city" value="{{$branchData->billing_city}}" id="bill_city" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">State</label>

                            <div class="col-sm-6">
                              <input name="bill_state" value="{{$branchData->billing_state}}" id="bill_state" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">Zip Code</label>

                            <div class="col-sm-6">
                              <input name="bill_zipCode" value="{{$branchData->billing_zip_code}}" id="bill_zipCode" type="text" class="form-control" name="bill_zipCode">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-4 control-label require" for="inputEmail3">Country</label>

                            <div class="col-sm-6">
                              <select class="form-control select2" name="bill_country_id" id="bill_country_id">
                              <option value="">{{ trans('message.form.select_one') }}</option>
                              @foreach ($countries as $data)
                                <option value="{{$data->code}}" <?=isset($data->code) && $data->code == $branchData->billing_country_id ? 'selected':""?> >{{$data->country}}</option>
                              @endforeach
                              </select>
                            </div>
                          </div>

                          <h4 class="text-info text-center">Shipping Address <button id="copy" class="btn btn-default btn-xs" type="button">Copy Address</button></h4>

                          <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputEmail3">Street</label>

                            <div class="col-sm-6">
                              <input name="ship_street" value="{{$branchData->shipping_street}}" id="ship_street" type="text" class="form-control">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputEmail3">City</label>

                            <div class="col-sm-6">
                              <input name="ship_city" value="{{$branchData->shipping_city}}" id="ship_city" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputEmail3">State</label>

                            <div class="col-sm-6">
                              <input name="ship_state" value="{{$branchData->shipping_state}}" id="ship_state" type="text" class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputEmail3">Zip Code</label>

                            <div class="col-sm-6">
                              <input name="ship_zipCode" value="{{$branchData->shipping_zip_code}}" id="ship_zipCode" type="text" class="form-control">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputEmail3">Country</label>

                            <div class="col-sm-6">
                              <select class="form-control select2" name="ship_country_id" id="ship_country_id">
                              <option value="">{{ trans('message.form.select_one') }}</option>
                              @foreach ($countries as $data)
                                <option value="{{$data->code}}" <?=isset($data->code) && $data->code == $branchData->shipping_country_id ? 'selected':""?> >{{$data->country}}</option>
                              @endforeach
                              </select>
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="btn_save" class="col-sm-4 control-label"></label>
                            <div class="col-sm-6">
                              <button type="submit" class="btn btn-primary">{{ trans('message.form.update') }}</button>
                            </div>
                          </div>
                </form>
            </div>
            </div>
            <!-- /.box-body -->
          </div>
        
        <!-- /.box-footer-->
  
    </section>
@endsection


@section('js')
    <script type="text/javascript">

    $('#addBranch').validate({
        rules: {
            br_name: {
                required: true
            },
            br_contact: {
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
            }                      
        }
    });

    $('#copy').on('click', function() {
        $('#ship_street').val($('#bill_street').val());
        $('#ship_city').val($('#bill_city').val());
        $('#ship_state').val($('#bill_state').val());
        $('#ship_zipCode').val($('#bill_zipCode').val());
        $('#ship_cn').val($('#bill_cn').val());
        $("#ship_country_id").val($('#bill_country_id').val());
    })

    </script>
@endsection