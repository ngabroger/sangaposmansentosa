<script src="../assets/resources/js/core/popper.min.js"></script>
<script src="../assets/resources/js/core/bootstrap.min.js"></script>
<script src="../assets/resources/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/resources/js/plugins/smooth-scrollbar.min.js"></script>
<script src="../assets/resources/js/plugins/chartjs.min.js"></script>
<script src="../assets/resources/js/plugins/material-dashboard.min.js"></script>

<script>
  var win = navigator.platform.indexOf('Win') > -1;
  if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
      damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
  }
</script>