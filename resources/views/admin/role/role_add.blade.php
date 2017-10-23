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
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('message.form.user_role_create') }}</h3>
            </div>
            <!-- /.box-header -->
            <form action="{{ url('save-role') }}" method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label require" for="inputEmail3">{{ trans('message.table.role_name') }}</label>
                  <div class="col-sm-10">
                    <input type="text" placeholder="{{ trans('message.table.role_name') }}" class="form-control valdation_check" id="nm" name="role">
                    <span id="val_nm" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label require" for="inputEmail3">{{ trans('message.table.description') }}</label>

                  <div class="col-sm-10">
                    <input type="text" placeholder="{{ trans('message.table.description') }}" class="form-control valdation_check" id="ds" name="description">
                    <span id="val_ds" style="color: red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label require" for="inputEmail3">{{ trans('message.form.permission') }}</label>

                  <div class="col-sm-10">
                      
                      <table class="table table-bordered">
                          <tbody>
                          <tr>
                            <th>{{ trans('message.form.section_name') }}</th>
                            <th>{{ trans('message.form.areas') }}</th>
                          </tr>
                          <tr>
                            <td><dt><input type="checkbox" class="sec" name="section[category]" value="100" id="category"/>  {{ trans('message.form.category') }}</dt></td>
                            <td>
                                <input type="checkbox" class="cate" name="area[cat_add]" value="101"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="cate" name="area[cat_edit]" value="102"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="cate" name="area[cat_delete]" value="103"> {{ trans('message.form.Delete') }}&nbsp;&nbsp;
                            </td>
                          </tr>
                          <tr>
                            <td><dt><input type="checkbox" class="sec" name="section[unit]" value="600" id="unit"/>  {{ trans('message.form.unit') }}</dt></td>
                            <td>
                                <input type="checkbox" class="unit" name="area[unit_add]" value="601"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="unit" name="area[unit_edit]" value="602"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="unit" name="area[unit_delete]" value="603"> {{ trans('message.form.Delete') }}&nbsp;&nbsp;
                            </td>
                          </tr>
                          <tr>
                            <td><dt><input type="checkbox" class="sec" name="section[loc]" value="200" id="loc">  {{ trans('message.header.location') }}</dt></td>
                            <td>
                                <input type="checkbox" class="loca" name="area[loc_add]" value="201" id="201"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="loca" name="area[loc_edit]" value="202" id="202"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="loca" name="area[loc_delete]" value="203" id="203"> {{ trans('message.form.Delete') }}&nbsp;&nbsp;
                            </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[item]" value="300" id="item">  {{ trans('message.header.item') }}</dt></td>
                            <td>
                                <input type="checkbox" class="item" name="area[item_add]" value="301"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="item" name="area[item_edit]" value="302"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="item" name="area[item_delete]" value="303"> {{ trans('message.form.Delete') }}&nbsp;&nbsp;
                            </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[user]" value="400" id="user">  {{ trans('message.sidebar.users') }}</dt></td>
                            <td>
                                <input type="checkbox" class="user" name="area[user_add]" value="401"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="user" name="area[user_edit]" value="402"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="user" name="area[user_delete]" value="403"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>
                          <tr>
                              <td><dt> <input type="checkbox" class="sec" name="section[role]" value="500" id="role">  {{ trans('message.sidebar.role') }}</dt></td> 
                              <td style="visibility: hidden;">
                                <input type="checkbox" class="role" name="area[user_role]" value="501"  > Access
                              </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[customer]" value="700" id="customer">  {{ trans('message.sidebar.customer') }}</dt></td>
                            <td>
                                <input type="checkbox" class="customer" name="area[customer_add]" value="701"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="customer" name="area[customer_edit]" value="702"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="customer" name="area[customer_delete]" value="703"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[sales]" value="800" id="sales">  {{ trans('message.sidebar.sales') }}</dt></td>
                            <td>
                                <input type="checkbox" class="sales" name="area[sales_add]" value="801"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="sales" name="area[sales_edit]" value="802"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="sales" name="area[sales_delete]" value="803"> {{ trans('message.form.Delete') }}&nbsp;&nbsp;
                                
                            </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[purchese]" value="900" id="purchese">  {{ trans('message.extra_text.purchase') }}</dt></td>
                            <td>
                                <input type="checkbox" class="purchese" name="area[purchese_add]" value="901"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="purchese" name="area[purchese_edit]" value="902"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="purchese" name="area[purchese_delete]" value="903"> {{ trans('message.form.Delete') }}&nbsp;&nbsp;
                            </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[supplier]" value="1000" id="supplier">  {{ trans('message.sidebar.supplier') }}</dt></td>
                            <td>
                                <input type="checkbox" class="supplier" name="area[supplier_add]" value="1001"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="supplier" name="area[supplier_edit]" value="1002"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="supplier" name="area[supplier_delete]" value="1003"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>

                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[transfer]" value="1100" id="transfer">  {{ trans('message.sidebar.transfer') }}</dt></td>
                            <td>
                                <input type="checkbox" class="transfer" name="area[transfer_add]" value="1101"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="transfer" name="area[transfer_edit]" value="1102"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="transfer" name="area[transfer_delete]" value="1103"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr> 

                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[order]" value="1200" id="order">  {{ trans('message.extra_text.order') }}</dt></td>
                            <td>
                                <input type="checkbox" class="order" name="area[order_add]" value="1201"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="order" name="area[order_edit]" value="1202"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="order" name="area[order_delete]" value="1203"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>  

                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[shipment]" value="1300" id="shipment">  {{ trans('message.extra_text.shipment') }}</dt></td>
                            <td>
                                <input type="checkbox" class="shipment" name="area[shipment_add]" value="1301"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="shipment" name="area[shipment_edit]" value="1302"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="shipment" name="area[shipment_delete]" value="1303"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr> 

                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[payment]" value="1400" id="payment">  {{ trans('message.extra_text.payment') }}</dt></td>
                            <td>
                                <input type="checkbox" class="payment" name="area[payment_add]" value="1401"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="payment" name="area[payment_edit]" value="1402"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="payment" name="area[payment_delete]" value="1403"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr> 

                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[backup]" value="1500" id="backup">  {{ trans('message.extra_text.backup') }}</dt></td>
                            <td>
                                <input type="checkbox" class="backup" name="area[backup_add]" value="1501"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="backup" name="area[backup_download]" value="1502"> {{ trans('message.extra_text.download') }}&nbsp;&nbsp;
                            </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[email]" value="1600" id="email">  {{ trans('message.extra_text.email') }}</dt></td>
                            <td>
                                <input type="checkbox" class="email" name="area[email_add]" value="1601"> {{ trans('message.extra_text.setup') }}&nbsp;&nbsp;
                            </td>
                          </tr>

                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[emailtemp]" value="1700" id="emailtemp">  {{ trans('message.extra_text.emailTemp') }}</dt></td>
                          </tr>

                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[preference]" value="1800" id="preference">  {{ trans('message.table.preference') }}</dt></td>
                          </tr>


                         <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[tax]" value="1900" id="tax">  {{ trans('message.table.tax') }}</dt></td>
                            <td>
                                <input type="checkbox" class="tax" name="area[tax_add]" value="1901"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="tax" name="area[tax_edit]" value="1902"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="tax" name="area[tax_delete]" value="1903"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>

                         <tr>
                            <td><dt> <input type="checkbox" class="salestype" name="section[salestype]" value="2000" id="saletype">  {{ trans('message.form.sales_type') }}</dt></td>
                            <td>
                                <input type="checkbox" class="salestype" name="area[salestype_add]" value="2001"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="salestype" name="area[salestype_edit]" value="2002"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="salestype" name="area[salestype_delete]" value="2003"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>

                         <tr>
                            <td><dt> <input type="checkbox" class="currencies" name="section[currencies]" value="2100" id="currencies">  {{ trans('message.form.currency') }}</dt></td>
                            <td>
                                <input type="checkbox" class="currencies" name="area[currencies_add]" value="2101"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="currencies" name="area[currencies_edit]" value="2102"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="currencies" name="area[currencies_delete]" value="2103"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>

                         <tr>
                            <td><dt> <input type="checkbox" class="paymentterm" name="section[paymentterm]" value="2200" id="paymentterm">  {{ trans('message.form.payment_term') }}</dt></td>
                            <td>
                                <input type="checkbox" class="paymentterm" name="area[paymentterm_add]" value="2201"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="paymentterm" name="area[paymentterm_edit]" value="2202"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="paymentterm" name="area[paymentterm_delete]" value="2203"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>

                         <tr>
                            <td><dt> <input type="checkbox" class="paymentmethod" name="section[paymentmethod]" value="2300" id="paymentmethod">  {{ trans('message.extra_text.payment_method') }}</dt></td>
                            <td>
                                <input type="checkbox" class="paymentmethod" name="area[paymentmethod_add]" value="2301"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="paymentmethod" name="area[paymentmethod_edit]" value="2302"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="paymentmethod" name="area[paymentmethod_delete]" value="2303"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>



                          <tr>
                            <td><dt> <input type="checkbox" class="sec" name="section[companysetting]" value="2400" id="companysetting">  {{ trans('message.extra_text.company_setting') }}</dt></td>
                          </tr>

                        </tbody>
                      </table>
                      <span id="error" style="color: red"></span>
                  </div>

                  
                </div>
            
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{{ url('user-role') }}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary btn-flat pull-right" type="submit">{{ trans('message.form.submit') }}</button>
              </div>
              <!-- /.box-footer -->
            </form>
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
      $(document).ready(function(){

            $("#category").change(function () {
                $(".cate").prop('checked', $(this).prop("checked"));
            });

            $("#unit").change(function () {
                $(".unit").prop('checked', $(this).prop("checked"));
            });

            $("#loc").change(function () {
                $(".loca").prop('checked', $(this).prop("checked"));
            });


            $("#item").change(function () {
                $(".item").prop('checked', $(this).prop("checked"));
            });

            $("#user").change(function () {
                $(".user").prop('checked', $(this).prop("checked"));
            });

            $("#role").change(function () {
                $(".role").prop('checked', $(this).prop("checked"));
            });
            
            $("#customer").change(function () {
                $(".customer").prop('checked', $(this).prop("checked"));
            });

            $("#sales").change(function () {
                $(".sales").prop('checked', $(this).prop("checked"));
            });

            $("#purchese").change(function () {
                $(".purchese").prop('checked', $(this).prop("checked"));
            });

            $("#supplier").change(function () {
                $(".supplier").prop('checked', $(this).prop("checked"));
            });
            $("#transfer").change(function () {
                $(".transfer").prop('checked', $(this).prop("checked"));
            });

            $("#order").change(function () {
                $(".order").prop('checked', $(this).prop("checked"));
            });
            $("#shipment").change(function () {
                $(".shipment").prop('checked', $(this).prop("checked"));
            });

            $("#payment").change(function () {
                $(".payment").prop('checked', $(this).prop("checked"));
            });

            $("#backup").change(function () {
                $(".backup").prop('checked', $(this).prop("checked"));
            });

            $("#email").change(function () {
                $(".email").prop('checked', $(this).prop("checked"));
            });

            $("#emailTemp").change(function () {
                $(".emailTemp").prop('checked', $(this).prop("checked"));
            });

            $("#tax").change(function () {
                $(".tax").prop('checked', $(this).prop("checked"));
            });

            $("#saletype").change(function () {
                $(".saletype").prop('checked', $(this).prop("checked"));
            });

            $("#currencies").change(function () {
                $(".currencies").prop('checked', $(this).prop("checked"));
            });   
            
             $("#paymentterm").change(function () {
                $(".paymentterm").prop('checked', $(this).prop("checked"));
            });
             $("#paymentmethod").change(function () {
                $(".paymentmethod").prop('checked', $(this).prop("checked"));
            });   


            $('#myform1').on('submit',function(e){
                    var flag = 0;
                    
                    $('.valdation_check').each(function(){
                        var id = $(this).attr('id');
                        console.log($('#'+id).val());
                        if ($('#'+id).val() == '')
                        {
                            $('#val_'+id).html("{{ trans('message.error.required') }}");
                            flag = 1;
                        }
                    });

                    if (jQuery(".sec").is(":checked")){
                        
                      }else{
                        $('#error').html("{{ trans('message.error.required_section') }}");
                        flag = 1;
                      }



                    if(flag == 1) {
                        e.preventDefault();
                    }
            });
            $(".valdation_check").on('keypress keyup',function(){
                var nm = $(this).attr("id");
                $('#val_'+nm).html("");
            });


      });
    </script>
@endsection