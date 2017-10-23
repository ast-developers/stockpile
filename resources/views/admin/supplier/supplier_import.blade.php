@extends('layouts.app')


@section('content')

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
        
        <div class="row">
          
          <div class="col-md-12">
          
            <div class="box box-info">
            <div class="box-header with-border">
              <a href="{{ URL::to('supplierdownloadCsv/sample') }}"><button class="btn btn-default btn-flat btn-border-purple"><span class="fa fa-download"> &nbsp;</span>Download Sample</button></a>
            </div>
            
            <div class="box-body">
            <div class="tab-content">
                <p>Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is UTF-8 to avoid unnecessary encoding problems.</p>
                
                <small class="text-red">Duplicate email rows wont be imported</small><br><br>
            
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Supplier</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Street</th>
                      <th>City</th>
                      <th>State</th>
                      <th>Zipcode</th>
                      <th>Country</th>

                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>John De</td>
                      <td>example@exmample.com</td>
                      <td>1235678</td>
                      <td>Washingto</td>
                      <td>Washingto</td>
                      <td>WA 123</td>
                      <td>12345</td>
                      <td>US</td>
                      
                    </tr>
                  </tbody>
                </table>
            </div><br/><br/>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ url('supplierimportcsv') }}" method="post" id="myform1" class="form-horizontal" enctype="multipart/form-data">
            {!! csrf_field() !!}
                <div class="form-group">
                  <label class="col-sm-2 control-label require" for="inputEmail3"> Choose CSV File</label>

                  <div class="col-sm-5">
                    <input type="file" class="form-control input-file-field valdation_check" id="name" name="import_file">
                    <span id="val_name" style="color: red"></span>
                  </div>
                </div><br/><br/>
            
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{{ url('item-category') }}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary btn-flat pull-right" type="submit">{{ trans('message.form.submit') }}</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          </div>
          </div>
          
        </div>
        
        <!-- /.box-footer-->
      
      <!-- /.box -->

    </section>
@endsection

@section('js')
    <script type="text/javascript">
        $('#myform1').on('submit',function(e) {
                var flag = 0;
                $('.valdation_check').each(function() {
                    var id = $(this).attr('id');
                    console.log($('#'+id).val());
                    if($('#'+id).val() == '')
                    {
                        $('#val_'+id).html("{{ trans('message.error.required') }}");
                        flag = 1;
                    }
                });
                $('.valdation_select').each(function() {
                    var id = $(this).attr('id');
                    //console.log($('#'+id).val());
                    if ($('#'+id).val() == '') {
                    
                        $('#val_'+id).html("{{ trans('message.error.required') }}");
                        flag = 1;
                        //console.log('country '+flag);
                    }
                });
                if (flag == 1) {
                    e.preventDefault();
                }
        });
        $(".valdation_check").on('keypress keyup',function() {
            var nm = $(this).attr("id");
            $('#val_'+nm).html("");
        });
        $(".valdation_select").on('click',function() {
            var nm = $(this).attr("id");
            $('#val_'+nm).html("");
        });
    </script>
@endsection
