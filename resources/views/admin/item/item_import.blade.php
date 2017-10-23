@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
        <div class="row">
          <div class="col-md-12">
            <div class="box box-info">
            <div class="box-header with-border">
              <a href="{{ URL::to('itemdownloadcsv/sample') }}"><button class="btn btn-default btn-flat btn-border-info"><span class="fa fa-download"> &nbsp;</span>Download Sample</button></a>
            </div>
            <div class="box-body">
            <div class="tab-content">
                <p>Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is UTF-8 to avoid unnecessary encoding problems.</p>
                
                <small class="text-red">Duplicate Item ID rows wont be imported</small><br><br>
            
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Item ID</th>
                      <th>Item Name</th>
                      <th>Long Description</th>
                      <th>Category Name</th>
                      <th>Unit</th>
                      <th>Tax Type</th>
                      <th>Purchase Price</th>
                      <th>Retail Price</th>
                      <th>Wholesale Price</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>DELL</td>
                      <td>Dell computer</td>
                      <td>This is a dell desktop computer</td>
                      <td>Computer</td>
                      <td>pc</td>
                      <td>Normal</td>
                      <td>50</td>
                      <td>60</td>
                      <td>55</td>
                    </tr>
                  </tbody>
                </table>
            </div><br/><br/>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ url('itemimportcsv') }}" method="post" id="myform1" class="form-horizontal" enctype="multipart/form-data">
            {!! csrf_field() !!}
                <div class="form-group">
                  <label class="col-sm-2 control-label require" for="inputEmail3"> Choose CSV File</label>

                  <div class="col-sm-5">
                    <input type="file" class="form-control valdation_check input-file-field" id="name" name="import_file">
                    <span id="val_name" style="color: red"></span>
                  </div>
                </div><br/><br/>
            
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{{ url('item') }}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
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
