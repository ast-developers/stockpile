<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ Session::get('company_name') }} | {{ucfirst(trans("message.sidebar.$menu"))}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="_token" content="{!! csrf_token() !!}"/>
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ asset('public/bootstrap/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('public/bootstrap/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('public/bootstrap/css/ionicons.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('public/plugins/datatables/dataTables.bootstrap.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('public/plugins/select2/select2.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('public/dist/css/AdminLTE.min.css') }}">
  <!--<link rel="stylesheet" href="{{ asset('public/dist/css/custom.css') }}">-->
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset('public/dist/css/skins/_all-skins.min.css') }}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ asset('public/plugins/datepicker/datepicker3.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/css/jquery-ui.min.css') }}" type="text/css" /> 
  <link rel="stylesheet" href="{{ asset('public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('public/dist/css/custom.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/css/bootstrap-confirm-delete.css') }}">

  <script type="text/javascript">
    var SITE_URL = "{{URL::to('/')}}";
  </script>
  <link rel='shortcut icon' href="{{URL::to('/')}}/favicon.ico" type='image/x-icon'/ >
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include('layouts.includes.header')
  <!-- Left side column. contains the logo and sidebar -->

  @include('layouts.includes.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @include('layouts.includes.notifications')
    
   {{-- @include('layouts.includes.breadcrumb') --}}
    
    @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('layouts.includes.footer')

  <!-- Control Sidebar -->
  @include('layouts.includes.sidebar_right')
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
@include('layouts.includes.script')
@yield('js')
</body>
</html>