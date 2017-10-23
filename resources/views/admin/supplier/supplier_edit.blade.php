@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">

    <div class="box box-default">
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
           <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.supplier_single') }}</div>
          </div> 
        </div>
      </div>
    </div>

        <div class="box">
           <div class="panel-body">
                <ul class="nav nav-tabs cus" role="tablist">
                    <li class="active">
                      <a href="{{url("edit-supplier/$supplierData->supplier_id")}}" >{{ trans('message.sidebar.profile') }}</a>
                    </li>
                    <li>
                      <a href="{{url("supplier/orders/$supplierData->supplier_id")}}" >{{ trans('message.extra_text.purchase_orders') }}</a>
                    </li>
               </ul>
              <div class="clearfix"></div>
           </div>
        </div>

      <!-- Default box -->
        
        <div class="box">
          <div class="box-body">
              <h4 class="text-info text-center">{{ trans('message.table.update_suppiler') }}</h4>
                <!-- form start -->
              <form action='{{ url("update-supplier/$supplierData->supplier_id") }}' method="post" id="myform1" class="form-horizontal">
              <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
              <div class="box-body">
              <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.table.supp_name') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.form.full_name') }}" class="form-control valdation_check" id="fname" name="supp_name" value="{{$supplierData->supp_name}}">
                    <span id="val_fname" style="color: red"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.email') }}</label>

                  <div class="col-sm-6">
                    <input type="text" placeholder="Supplier Short Name" class="form-control valdation_check" id="lname" name="email" value="{{$supplierData->email
                      }}" readonly>
                    <span id="val_lname" style="color: red"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.table.phone') }}</label>

                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.table.phone') }}" class="form-control valdation_check" id="name" name="contact" value="{{$supplierData->contact}}">
                    <span id="val_name" style="color: red"></span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.extra_text.street') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.extra_text.street') }}" class="form-control valdation_check" id="address" name="address" value="{{$supplierData->address}}">
                  </div>
                </div>

            
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.extra_text.city') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.extra_text.city') }}" class="form-control" id="city" name="city" value="{{$supplierData->city}}">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.extra_text.state') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.extra_text.state') }}" class="form-control" id="state" name="state" value="{{$supplierData->state}}">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.extra_text.zipcode') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ trans('message.extra_text.zipcode') }}" class="form-control" id="zipcode" name="zipcode" value="{{$supplierData->zipcode}}">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.extra_text.country') }}</label>
                  <div class="col-sm-6">
                        <select class="form-control select2" name="country" id="country">
                        <option value="">{{ trans('message.form.select_one') }}</option>
                        @foreach ($countries as $data)
                          <option value="{{$data->code}}" <?= ($data->code == $supplierData->country) ? 'selected' : ''?>>{{$data->country}}</option>
                        @endforeach
                        </select>
                  </div>
                </div> 
                
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.form.status') }}</label>

                  <div class="col-sm-6">
                    <select class="form-control valdation_select" name="inactive" >
                      
                      <option value="0" <?=isset($supplierData->inactive) && $supplierData->inactive ==  0? 'selected':""?> >Active</option>
                      <option value="1"  <?=isset($supplierData->inactive) && $supplierData->inactive == 1 ? 'selected':""?> >Inactive</option>
                    </select>
               @if(!empty(Session::get('supplier_delete')))

              <div>
                <br>
                <a href="{{ url('supplier') }}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary pull-right btn-flat" type="submit">{{ trans('message.form.submit') }}</button>
              </div>
              @endif

                  </div>
                </div>

              </div>
              </div>
              </div>
              <!-- /.box-body -->

              <!-- /.box-footer -->
            </form>

          </div>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript">
    $(".select2").select2();
    $('#myform1').validate({
        rules: {
            supp_name: {
                required: true
            },
            email: {
                required: true
            },
            address:{
              required : true
            }, 
            city: {
                required: true
            },
            state: {
                required: true
            },

            country :{
              required : true
            }                               
        }
    });
    </script>
@endsection