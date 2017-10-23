@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
        <div class="row">
          <div class="col-md-offset-2 col-md-8">
            <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('message.table.preference') }}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ url('save-preference') }}" method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
              <div class="box-body">

                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.table.rows_per_page') }}:</label>

                  <div class="col-sm-6">
                    <select name="row_per_page" class="form-control select2" >
                        <option value="10" <?=isset($prefData[1]->value) && $prefData[1]->value == 10 ? 'selected':""?>>10</option>
                        <option value="25" <?=isset($prefData[1]->value) && $prefData[1]->value == 25 ? 'selected':""?>>25</option>
                        <option value="50" <?=isset($prefData[1]->value) && $prefData[1]->value == 50 ? 'selected':""?>>50</option>
                        <option value="100" <?=isset($prefData[1]->value) && $prefData[1]->value == 100 ? 'selected':""?>>100</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.table.date_format') }}:</label>

                  <div class="col-sm-6">
                    <select name="date_format" class="form-control select2" >
                        <option value="0" <?=isset($prefData[2]->value) && $prefData[2]->value == 0 ? 'selected':""?>>yyyymmdd {2016 12 31}</option>
                        <option value="1" <?=isset($prefData[2]->value) && $prefData[2]->value == 1 ? 'selected':""?>>ddmmyyyy {31 12 2016}</option>
                        <option value="2" <?=isset($prefData[2]->value) && $prefData[2]->value == 2 ? 'selected':""?>>mmddyyyy {12 31 2016}</option>
                        <option value="3" <?=isset($prefData[2]->value) && $prefData[2]->value == 3 ? 'selected':""?>>ddMyyyy &nbsp;&nbsp;&nbsp;{31 Dec 2016}</option>
                        <option value="4" <?=isset($prefData[2]->value) && $prefData[2]->value == 4 ? 'selected':""?>>yyyyMdd &nbsp;&nbsp;&nbsp;{2016 Dec 31}</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.table.date_separator') }}:</label>

                  <div class="col-sm-6">
                    <select name="date_sepa" class="form-control select2">

                        <option value="-" <?=isset($prefData[3]->value) && $prefData[3]->value == '-' ? 'selected':""?>>-</option>
                        <option value="/" <?=isset($prefData[3]->value) && $prefData[3]->value == '/' ? 'selected':""?>>/</option>
                        <option value="." <?=isset($prefData[3]->value) && $prefData[3]->value == '.' ? 'selected':""?>>.</option>
                        <option value="  " <?=isset($prefData[3]->value) && $prefData[3]->value == '  ' ? 'selected':""?>>  </option>
                    </select>
                    <br>
                    <br>
                    <button class="btn btn-primary btn-flat" type="submit">{{ trans('message.form.submit') }}</button>
                  </div>
                </div>

                <div class="form-group" style="display:none;">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ trans('message.table.percentage') }} (%):</label>

                  <div class="col-sm-6">
                    <select name="percentage" class="form-control select2" >
                        <option value="0" <?=isset($prefData[6]->value) && $prefData[6]->value == 0 ? 'selected':""?>>0</option>
                        <option value="1" <?=isset($prefData[6]->value) && $prefData[6]->value == 1 ? 'selected':""?>>1</option>
                        <option value="2" <?=isset($prefData[6]->value) && $prefData[6]->value == 2 ? 'selected':""?>>2</option>
                        <option value="3" <?=isset($prefData[6]->value) && $prefData[6]->value == 3 ? 'selected':""?>>3</option>
                        <option value="4" <?=isset($prefData[6]->value) && $prefData[6]->value == 4 ? 'selected':""?>>4</option>
                        <option value="5" <?=isset($prefData[6]->value) && $prefData[6]->value == 5 ? 'selected':""?>>5</option>
                        <option value="6" <?=isset($prefData[6]->value) && $prefData[6]->value == 6 ? 'selected':""?>>6</option>
                        <option value="7" <?=isset($prefData[6]->value) && $prefData[6]->value == 7 ? 'selected':""?>>7</option>
                        <option value="8" <?=isset($prefData[6]->value) && $prefData[6]->value == 8 ? 'selected':""?>>8</option>
                        <option value="9" <?=isset($prefData[6]->value) && $prefData[6]->value == 9 ? 'selected':""?>>9</option>
                        <option value="10" <?=isset($prefData[6]->value) && $prefData[6]->value == 10 ? 'selected':""?>>10</option>
                    </select>
                  </div>

                </div>

                <div class="form-group" style="display:none;">
                  <label class="col-sm-3 control-label" for="inputEmail3">Quantities:</label>

                  <div class="col-sm-6">
                    <select name="quantity" class="form-control select2" >
                        <option value="0" <?=isset($prefData[7]->value) && $prefData[7]->value == 0 ? 'selected':""?>>0</option>
                        <option value="1" <?=isset($prefData[7]->value) && $prefData[7]->value == 1 ? 'selected':""?>>1</option>
                        <option value="2" <?=isset($prefData[7]->value) && $prefData[7]->value == 2 ? 'selected':""?>>2</option>
                        <option value="3" <?=isset($prefData[7]->value) && $prefData[7]->value == 3 ? 'selected':""?>>3</option>
                        <option value="4" <?=isset($prefData[7]->value) && $prefData[7]->value == 4 ? 'selected':""?>>4</option>
                        <option value="5" <?=isset($prefData[7]->value) && $prefData[7]->value == 5 ? 'selected':""?>>5</option>
                        <option value="6" <?=isset($prefData[7]->value) && $prefData[7]->value == 6 ? 'selected':""?>>6</option>
                        <option value="7" <?=isset($prefData[7]->value) && $prefData[7]->value == 7 ? 'selected':""?>>7</option>
                        <option value="8" <?=isset($prefData[7]->value) && $prefData[7]->value == 8 ? 'selected':""?>>8</option>
                        <option value="9" <?=isset($prefData[7]->value) && $prefData[7]->value == 9 ? 'selected':""?>>9</option>
                        <option value="10" <?=isset($prefData[7]->value) && $prefData[7]->value == 10 ? 'selected':""?>>10</option>
                    </select>
                  </div>
                </div>
              </div>
            </form>
          </div>
          </div>          
        </div>        
        <!-- /.box-footer-->      
      <!-- /.box -->
    </section>
@endsection
@section('js')
    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        })
    </script>
@endsection