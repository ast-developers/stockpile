<footer class="main-footer">
    <div class="container">
<strong>Copyright &copy; <?php echo date('Y') ?> <a href="#">{{ Session::get('name') }}</a>.</strong> All rights
    reserved.
    </div>
    <!-- /.container -->
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="{{ asset('public/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('public/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('public/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('public/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{ asset('public/plugins/fastclick/fastclick.js') }}"></script><!-- AdminLTE App -->
<script src="{{ asset('public/dist/js/app.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script>
	$("#notifications").fadeTo(2000, 500).slideUp(500, function(){
	    $("#notifications").slideUp(500);
	});	
</script>
