<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Stock Manager | Installer</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ URL::asset('public/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/css/custom.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ URL::asset('public/dist/css/AdminLTE.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ URL::asset('public/plugins/iCheck/square/blue.css') }}">

    @yield('style')
</head>
<body class="hold-transition login-page">
<div class="login-box box-width">
<div class="login-box-body">
  
  <!-- /.login-logo -->
  
  @yield('content')
  <!-- /.login-box-body -->
</div>
</div>

<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="{{ URL::asset('public/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ URL::asset('public/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ URL::asset('public/plugins/iCheck/icheck.min.js') }}"></script>

@yield('script')
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
