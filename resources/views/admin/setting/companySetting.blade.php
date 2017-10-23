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
                <div class="col-md-12">
                 <div class="top-bar-title padding-bottom">{{ trans('message.table.company_setting') }}</div>
                </div> 
              </div>
            </div>
          </div>

          <div class="box">
          <div class="box-body">
            <!-- /.box-header -->
            <form action="{{ url('company/setting/save') }}" method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.name') }}</label>

                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="company_name" value="{{isset($companyData[9]->value) ? $companyData[9]->value : ''}}" >
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{trans('message.form.site_short_name')}}</label>

                  <div class="col-sm-6">
                    <input type="text" name="site_short_name" value="{{isset($companyData[5]->value) ? $companyData[5]->value : ''}}" class="form-control">
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.email') }}</label>

                  <div class="col-sm-6">
                    <input type="email" class="form-control" name="company_email" value="{{isset($companyData[10]->value) ? $companyData[10]->value : ''}}" >
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.table.phone') }}</label>

                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="company_phone" value="{{isset($companyData[11]->value) ? $companyData[11]->value : ''}}" >
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.invoice.street') }}</label>

                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="company_street" value="{{isset($companyData[12]->value) ? $companyData[12]->value : ''}}" >
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.invoice.city') }}</label>

                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="company_city" value="{{isset($companyData[13]->value) ? $companyData[13]->value : ''}}" >
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.invoice.state') }}</label>

                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="company_state" value="{{isset($companyData[14]->value) ? $companyData[14]->value : ''}}">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.invoice.zipcode') }}</label>

                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="company_zipCode" value="{{isset($companyData[15]->value) ? $companyData[15]->value : ''}}" >
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ trans('message.invoice.country') }}</label>

                  <div class="col-sm-6">
                    <select class="form-control select2" name="company_country_id" >
                    <option value="">{{ trans('message.form.select_one') }}</option>
                    @foreach ($countries as $data)
                      <option value="{{$data->country}}" <?=isset($companyData[16]->value) && $companyData[16]->value == $data->country ? 'selected':""?> >{{$data->country}}</option>
                    @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.table.default_language') }}</label>

                  <div class="col-sm-6">
                    <select name="dflt_lang" class="form-control" >
                        <option value="ar" <?=isset($companyData[17]->value) && $companyData[17]->value == 'ar' ? 'selected':""?> >Arabic</option>
                        <option value="en" <?=isset($companyData[17]->value) && $companyData[17]->value == 'en' ? 'selected':""?>>English</option>
                        <option value="ch" <?=isset($companyData[17]->value) && $companyData[17]->value == 'ch' ? 'selected':""?>>Chinese</option>
                        <option value="fr" <?=isset($companyData[17]->value) && $companyData[17]->value == 'fr' ? 'selected':""?>>French</option>
                        <option value="jp" <?=isset($companyData[17]->value) && $companyData[17]->value == 'jp' ? 'selected':""?>>Japanese</option>
                        <option value="rh" <?=isset($companyData[17]->value) && $companyData[17]->value == 'rh' ? 'selected':""?>>Russain</option>
                        <option value="sp" <?=isset($companyData[17]->value) && $companyData[17]->value == 'sp' ? 'selected':""?>>Spanish</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.table.default_currency') }}</label>

                  <div class="col-sm-6">
                    <select class="form-control" name="dflt_currency_id" >
                      <option value="">{{ trans('message.form.select_one') }}</option>
                    @foreach ($currencyData as $data)
                      <option value="{{$data->id}}" <?=isset($companyData[18]->value) && $companyData[18]->value == $data->id ? 'selected':""?> >{{$data->name}}</option>
                    @endforeach
                    </select>
                  </div>
                </div>
                

                <div class="form-group" style="display:none">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.table.price_type') }}</label>

                  <div class="col-sm-6">
                    <select class="form-control" name="sates_type_id" >
                      <option value="">{{ trans('message.form.select_one') }}</option>

                        @foreach ($saleTypes as $saleType)
                          <option value="{{$saleType->id}}" <?=isset($companyData[19]->value) && $companyData[19]->value == $saleType->id ? 'selected':""?> >{{$saleType->sales_type}}</option>
                        @endforeach
              
                    </select>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              
              <div class="box-footer">
                <button class="btn btn-primary pull-right btn-flat" type="submit" id="btnSubmits">{{ trans('message.form.submit') }}</button>
              </div>
              <!-- /.box-footer -->
            </form>
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
     $("#btnSubmit").on('click', function(){
      alert("This option is not available on demo version!");
      return false;
     });
    </script>
@endsection