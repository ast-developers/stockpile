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
            <form action='{{ url("update-role/$roleData->id") }}' method="post" class="form-horizontal">
            {!! csrf_field() !!}
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ trans('message.table.role_name') }}</label>

                  <div class="col-sm-10">
                    <input type="text" value="{{$roleData->role}}" class="form-control" name="role">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ trans('message.table.description') }}</label>

                  <div class="col-sm-10">
                    <input type="text" value="{{$roleData->description}}" class="form-control" name="description">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ trans('message.form.permission') }}</label>
                <?php
                  $section = unserialize($roleData->sections);
                  $areas = unserialize($roleData->areas);
                ?>
                  <div class="col-sm-10">
                    <table class="table table-bordered">
                          <tbody>
                          <tr>
                            <th>{{ trans('message.form.section_name') }}</th>
                            <th>{{ trans('message.form.areas') }}</th>
                          </tr>
                          <tr>
                            <td><dt><input type="checkbox" name="section[category]" <?=isset($section['category']) && $section['category']== 100 ? 'checked':""?> value="100" id="category"/>  {{ trans('message.form.category') }}</dt></td>
                            <td>
                                <input type="checkbox" class="cate" <?=isset($areas['cat_add']) && $areas['cat_add']== 101 ? 'checked':""?> name="area[cat_add]" value="101"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="cate" <?=isset($areas['cat_edit']) && $areas['cat_edit']== 102 ? 'checked':""?> name="area[cat_edit]" value="102"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="cate" <?=isset($areas['cat_delete']) && $areas['cat_delete']== 103 ? 'checked':""?> name="area[cat_delete]" value="103"> {{ trans('message.form.Delete') }}&nbsp;&nbsp;
                            </td>
                          </tr>
                          <tr>
                            <td><dt><input type="checkbox" name="section[unit]" <?=isset($section['unit']) && $section['unit']== 600 ? 'checked':""?> value="600" id="unit"/>  {{ trans('message.form.unit') }}</dt></td>
                            <td>
                                <input type="checkbox" class="unit" <?=isset($areas['unit_add']) && $areas['unit_add']== 601 ? 'checked':""?> name="area[unit_add]" value="601"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="unit" <?=isset($areas['unit_edit']) && $areas['unit_edit']== 602 ? 'checked':""?> name="area[unit_edit]" value="602"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="unit" <?=isset($areas['unit_delete']) && $areas['unit_delete']== 603 ? 'checked':""?> name="area[unit_delete]" value="603"> {{ trans('message.form.Delete') }}&nbsp;&nbsp;
                            </td>
                          </tr>
                          <tr>
                            <td><dt><input type="checkbox" name="section[loc]" <?=isset($section['loc']) && $section['loc']== 200 ? 'checked':""?> value="200" id="loc">  {{ trans('message.header.location') }}</dt></td>
                            <td>
                                <input type="checkbox" class="loca" <?=isset($areas['loc_add']) && $areas['loc_add']== 201 ? 'checked':""?> name="area[loc_add]" value="201" id="201"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="loca" <?=isset($areas['loc_edit']) && $areas['loc_edit']== 202 ? 'checked':""?> name="area[loc_edit]" value="202" id="202"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="loca" <?=isset($areas['loc_delete']) && $areas['loc_delete']== 203 ? 'checked':""?> name="area[loc_delete]" value="203" id="203"> {{ trans('message.form.Delete') }}&nbsp;&nbsp;
                            </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" name="section[item]" <?=isset($section['item']) && $section['item']== 300 ? 'checked':""?> value="300" id="item">  {{ trans('message.header.item') }}</dt></td>
                            <td>
                                <input type="checkbox" class="item" <?=isset($areas['item_add']) && $areas['item_add']== 301 ? 'checked':""?> name="area[item_add]" value="301"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="item" <?=isset($areas['item_edit']) && $areas['item_edit']== 302 ? 'checked':""?> name="area[item_edit]" value="302"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="item" <?=isset($areas['item_delete']) && $areas['item_delete']== 303 ? 'checked':""?> name="area[item_delete]" value="303"> {{ trans('message.form.Delete') }}&nbsp;&nbsp;
                            </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" name="section[user]" <?=isset($section['user']) && $section['user']== 400 ? 'checked':""?> value="400" id="user">  {{ trans('message.sidebar.users') }}</dt></td>
                            <td>
                                <input type="checkbox" class="user" <?=isset($areas['user_add']) && $areas['user_add']== 401 ? 'checked':""?> name="area[user_add]" value="401"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="user" <?=isset($areas['user_edit']) && $areas['user_edit']== 402 ? 'checked':""?> name="area[user_edit]" value="402"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="user" <?=isset($areas['user_delete']) && $areas['user_delete']== 403 ? 'checked':""?> name="area[user_delete]" value="403"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" class="user_role" name="section[role]" <?=isset($section['role']) && $section['role']== 500 ? 'checked':""?> value="500" id="role">  {{ trans('message.sidebar.role') }}</dt></td> 
                            <td style="visibility: hidden;">
                                <input type="checkbox" class="role" <?=isset($areas['user_role']) && $areas['user_role']== 501 ? 'checked':""?> name="area[user_role]" value="501"  > Access
                            </td>
                          </tr>

                          <tr>
                            <td><dt> <input type="checkbox" name="section[customer]" <?=isset($section['customer']) && $section['customer']== 700 ? 'checked':""?> value="700" id="customer">  {{ trans('message.sidebar.customer') }}</dt></td>
                            <td>
                                <input type="checkbox" class="customer" <?=isset($areas['customer_add']) && $areas['customer_add']== 701 ? 'checked':""?> name="area[customer_add]" value="701"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="customer" <?=isset($areas['customer_edit']) && $areas['customer_edit']== 702 ? 'checked':""?> name="area[customer_edit]" value="702"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="customer" <?=isset($areas['customer_delete']) && $areas['customer_delete']== 703 ? 'checked':""?> name="area[customer_delete]" value="703"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" name="section[sales]" <?=isset($section['sales']) && $section['sales']== 800 ? 'checked':""?> value="800" id="sales">  {{ trans('message.sidebar.sales') }}</dt></td>
                            <td>
                                <input type="checkbox" class="sales" <?=isset($areas['sales_add']) && $areas['sales_add']== 801 ? 'checked':""?> name="area[sales_add]" value="801"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="sales" <?=isset($areas['sales_edit']) && $areas['sales_edit']== 802 ? 'checked':""?> name="area[sales_edit]" value="802"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="sales" <?=isset($areas['sales_delete']) && $areas['sales_delete']== 803 ? 'checked':""?> name="area[sales_delete]" value="803"> {{ trans('message.form.Delete') }}

                            </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" <?=isset($section['purchese']) && $section['purchese']== 900 ? 'checked':""?> name="section[purchese]" value="900" id="purchese">  {{ trans('message.extra_text.purchase') }}</dt></td>
                            <td>
                                <input type="checkbox" class="purchese" <?=isset($areas['purchese_add']) && $areas['purchese_add']== 901 ? 'checked':""?> name="area[purchese_add]" value="901"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="purchese" <?=isset($areas['purchese_edit']) && $areas['purchese_edit']== 902 ? 'checked':""?> name="area[purchese_edit]" value="902"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="purchese" <?=isset($areas['purchese_delete']) && $areas['purchese_delete']== 903 ? 'checked':""?> name="area[purchese_delete]" value="903"> {{ trans('message.form.Delete') }}
                                
                            </td>
                          </tr>
                          <tr>
                            <td><dt> <input type="checkbox" <?=isset($section['supplier']) && $section['supplier']== 1000 ? 'checked':""?> name="section[supplier]" value="1000" id="supplier">  {{ trans('message.sidebar.supplier') }}</dt></td>
                            <td>
                                <input type="checkbox" class="supplier" <?=isset($areas['supplier_add']) && $areas['supplier_add']== 1001 ? 'checked':""?> name="area[supplier_add]" value="1001"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="supplier" <?=isset($areas['supplier_edit']) && $areas['supplier_edit']== 1002 ? 'checked':""?> name="area[supplier_edit]" value="1002"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="supplier" <?=isset($areas['supplier_delete']) && $areas['supplier_delete']== 1003 ? 'checked':""?> name="area[supplier_delete]" value="1003"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>
                          
                          <tr>
                            <td><dt> <input type="checkbox" class="sec" <?=isset($section['transfer']) && $section['transfer']== 1100 ? 'checked':""?> name="section[transfer]" value="1100" id="transfer">  {{ trans('message.sidebar.transfer') }}</dt></td>
                            <td>
                                <input type="checkbox" class="transfer" <?=isset($areas['transfer_add']) && $areas['transfer_add']== 1101 ? 'checked':""?> name="area[transfer_add]" value="1101"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="transfer" <?=isset($areas['transfer_edit']) && $areas['transfer_edit']== 1102 ? 'checked':""?> name="area[transfer_edit]" value="1102"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="transfer" <?=isset($areas['transfer_delete']) && $areas['transfer_delete']== 1103 ? 'checked':""?> name="area[transfer_delete]" value="1103"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr> 

                          <tr>
                            <td><dt> <input type="checkbox" class="sec" <?=isset($section['order']) && $section['order']== 1200 ? 'checked':""?> name="section[order]" value="1200" id="order">  {{ trans('message.extra_text.order') }}</dt></td>
                            <td>
                                <input type="checkbox" class="order" <?=isset($areas['order_add']) && $areas['order_add']== 1201 ? 'checked':""?> name="area[order_add]" value="1201"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="order" <?=isset($areas['order_edit']) && $areas['order_edit']== 1202 ? 'checked':""?> name="area[order_edit]" value="1202"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="order" <?=isset($areas['order_delete']) && $areas['order_delete']== 1203 ? 'checked':""?> name="area[order_delete]" value="1203"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>                          

                          <tr>
                            <td><dt> <input type="checkbox" class="sec" <?=isset($section['shipment']) && $section['shipment']== 1300 ? 'checked':""?> name="section[shipment]" value="1300" id="shipment">  {{ trans('message.extra_text.shipment') }}</dt></td>
                            <td>
                                <input type="checkbox" class="shipment" <?=isset($areas['shipment_add']) && $areas['shipment_add']== 1301 ? 'checked':""?> name="area[shipment_add]" value="1301"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="shipment" <?=isset($areas['shipment_edit']) && $areas['shipment_edit']== 1302 ? 'checked':""?> name="area[shipment_edit]" value="1302"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="shipment" <?=isset($areas['shipment_delete']) && $areas['shipment_delete']== 1303 ? 'checked':""?> name="area[shipment_delete]" value="1303"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>

                          <tr>
                            <td><dt> <input type="checkbox" class="sec" <?=isset($section['payment']) && $section['payment']== 1400 ? 'checked':""?> name="section[payment]" value="1400" id="payment">  {{ trans('message.extra_text.payment') }}</dt></td>
                            <td>
                                <input type="checkbox" class="payment" <?=isset($areas['payment_add']) && $areas['payment_add']== 1401 ? 'checked':""?> name="area[payment_add]" value="1401"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="payment" <?=isset($areas['payment_edit']) && $areas['payment_edit']== 1402 ? 'checked':""?> name="area[payment_edit]" value="1402"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="payment" <?=isset($areas['payment_delete']) && $areas['payment_delete']== 1403 ? 'checked':""?> name="area[payment_delete]" value="1403"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>


                          <tr>
                            <td><dt> <input type="checkbox" class="sec" <?=isset($section['backup']) && $section['backup']== 1500 ? 'checked':""?> name="section[backup]" value="1500" id="backup">  {{ trans('message.extra_text.backup') }}</dt></td>
                            <td>
                                <input type="checkbox" class="backup" <?=isset($areas['backup_add']) && $areas['backup_add']== 1501 ? 'checked':""?> name="area[backup_add]" value="1501"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="backup" <?=isset($areas['backup_download']) && $areas['backup_download']== 1502 ? 'checked':""?> name="area[backup_download]" value="1502"> {{ trans('message.extra_text.download') }}&nbsp;&nbsp;
                            </td>
                          </tr>
                          
                          <tr>
                            <td><dt> <input type="checkbox" class="sec" <?=isset($section['email']) && $section['email']== 1600 ? 'checked':""?> name="section[email]" value="1600" id="email">  {{ trans('message.extra_text.email') }}</dt></td>
                            <td>
                                <input type="checkbox" class="email" <?=isset($areas['email_add']) && $areas['email_add']== 1601 ? 'checked':""?> name="area[email_add]" value="1601"> {{ trans('message.extra_text.setup') }}&nbsp;&nbsp;
                            </td>
                          </tr>

                          <tr>
                            <td><dt>
                                <input type="checkbox" class="emailtemp" <?=isset($areas['emailtemp']) && $areas['emailtemp']== 1700 ? 'checked':""?> name="area[emailtemp]" value="1700"> {{ trans('message.extra_text.emailTemp') }}&nbsp;&nbsp;
                            </dt>
                            </td>
                          </tr>                    

                          <tr>
                            <td>
                              <dt>
                                <input type="checkbox" class="preference" <?=isset($areas['preference']) && $areas['preference']== 1800 ? 'checked':""?> name="area[preference]" value="1800"> {{ trans('message.table.preference') }}&nbsp;&nbsp;
                           </dt>
                            </td>
                          </tr>

                          <tr>
                            <td><dt> <input type="checkbox" class="sec" <?=isset($section['tax']) && $section['tax']== 1900 ? 'checked':""?> name="section[tax]" value="1900" id="tax">  {{ trans('message.table.tax') }}</dt></td>
                            <td>
                                <input type="checkbox" class="tax" <?=isset($areas['tax_add']) && $areas['tax_add']== 1901 ? 'checked':""?> name="area[tax_add]" value="1901"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="tax" <?=isset($areas['tax_edit']) && $areas['tax_edit']== 1902 ? 'checked':""?> name="area[tax_edit]" value="1902"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="tax" <?=isset($areas['tax_delete']) && $areas['tax_delete']== 1903 ? 'checked':""?> name="area[tax_delete]" value="1903"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>

                          <tr>
                            <td><dt><input type="checkbox" class="salestype" <?=isset($section['salestype']) && $section['salestype']== 2000 ? 'checked':""?> name="section[salestype]" value="2000" id="salestype">  {{ trans('message.form.sales_type') }}</dt></td>
                            <td>
                                <input type="checkbox" class="salestype" <?=isset($areas['salestype_add']) && $areas['salestype_add']== 2001 ? 'checked':""?> name="area[salestype_add]" value="2001"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="salestype" <?=isset($areas['salestype_edit']) && $areas['salestype_edit']== 2002 ? 'checked':""?> name="area[salestype_edit]" value="2002"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="salestype" <?=isset($areas['salestype_delete']) && $areas['salestype_delete']== 2003 ? 'checked':""?> name="area[salestype_delete]" value="2003"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>


                          <tr>
                            <td><dt><input type="checkbox" class="currencies" <?=isset($section['currencies']) && $section['currencies']== 2100 ? 'checked':""?> name="section[currencies]" value="2100" id="currencies">  {{ trans('message.form.currency') }}</dt></td>
                            <td>
                                <input type="checkbox" class="currencies" <?=isset($areas['currencies_add']) && $areas['currencies_add']== 2101 ? 'checked':""?> name="area[currencies_add]" value="2101"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="currencies" <?=isset($areas['currencies_edit']) && $areas['currencies_edit']== 2102 ? 'checked':""?> name="area[currencies_edit]" value="2102"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="currencies" <?=isset($areas['currencies_delete']) && $areas['currencies_delete']== 2103 ? 'checked':""?> name="area[currencies_delete]" value="2103"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>

                          <tr>
                            <td><dt><input type="checkbox" class="paymentterm" <?=isset($section['paymentterm']) && $section['paymentterm']== 2200 ? 'checked':""?> name="section[paymentterm]" value="2200" id="paymentterm">  {{ trans('message.form.currency') }}</dt></td>
                            <td>
                                <input type="checkbox" class="paymentterm" <?=isset($areas['paymentterm_add']) && $areas['paymentterm_add']== 2201 ? 'checked':""?> name="area[paymentterm_add]" value="2201"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="paymentterm" <?=isset($areas['paymentterm_edit']) && $areas['paymentterm_edit']== 2202 ? 'checked':""?> name="area[paymentterm_edit]" value="2202"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="paymentterm" <?=isset($areas['paymentterm_delete']) && $areas['paymentterm_delete']== 2203 ? 'checked':""?> name="area[paymentterm_delete]" value="2203"> {{ trans('message.form.Delete') }}
                            </td>
                          </tr>

                          <tr>
                            <td><dt><input type="checkbox" class="paymentmethod" <?=isset($section['paymentmethod']) && $section['paymentmethod']== 2300 ? 'checked':""?> name="section[paymentmethod]" value="2300" id="paymentmethod">  {{ trans('message.extra_text.payment_method') }}</dt></td>
                            <td>
                                <input type="checkbox" class="paymentmethod" <?=isset($areas['paymentmethod_add']) && $areas['paymentmethod_add']== 2301 ? 'checked':""?> name="area[paymentmethod_add]" value="2301"> {{ trans('message.form.Add') }}&nbsp;&nbsp;
                                <input type="checkbox" class="paymentmethod" <?=isset($areas['paymentmethod_edit']) && $areas['paymentmethod_edit']== 2302 ? 'checked':""?> name="area[paymentmethod_edit]" value="2302"> {{ trans('message.form.Edit') }}&nbsp;&nbsp;
                                <input type="checkbox" class="paymentmethod" <?=isset($areas['paymentmethod_delete']) && $areas['paymentmethod_delete']== 2303 ? 'checked':""?> name="area[paymentmethod_delete]" value="2303"> {{ trans('message.form.Delete') }}
                            
                            </td>
                          </tr>

                          <tr>
                            <td><dt>
                                <input type="checkbox" class="companysetting" <?=isset($areas['companysetting']) && $areas['companysetting']== 2400 ? 'checked':""?> name="area[companysetting]" value="2400"> {{ trans('message.extra_text.company_setting') }}&nbsp;&nbsp;
                              </dt>
                            </td>
                          </tr> 

                        </tbody>
                      </table>
                  </div>
                </div>
            
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{{ url('user-role') }}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                @if(!empty(Session::get('user_role')))
                <button class="btn btn-primary btn-flat pull-right" type="submit">{{ trans('message.form.update') }}</button>
                @endif
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

            $("#emailtemp").change(function () {
                $(".emailtemp").prop('checked', $(this).prop("checked"));
            });

            $("#tax").change(function () {
                $(".tax").prop('checked', $(this).prop("checked"));
            });

            $("#salestype").change(function () {
                $(".salestype").prop('checked', $(this).prop("checked"));
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