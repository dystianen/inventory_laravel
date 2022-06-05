<!--   Core JS Files   -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="soft-ui/js/core/popper.min.js"></script>
<script src="soft-ui/js/core/bootstrap.min.js"></script>
<script src="soft-ui/js/plugins/perfect-scrollbar.min.js"></script>
<script src="soft-ui/js/plugins/smooth-scrollbar.min.js"></script>
<script src="soft-ui/js/plugins/chartjs.min.js"></script>

<script>
  var win = navigator.platform.indexOf('Win') > -1;
  if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
      damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
  }
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="soft-ui/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
</body>

</html>