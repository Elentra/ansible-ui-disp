<?php require_once("template/header.tpl"); ?>
<div class="container">
  <header class="navbar">
    <div class="navbar navbar-inverse">
      <a class="brand" href="#">Ansible UI</a>
      <?php require_once("template/nav.tpl"); ?>
      <?php require_once("template/nav-pane.tpl"); ?>
    </div>
  </header>
  <div class="row-fluid">
    <div class="span3 bs-docs-sidebar">
      <h3>Users</h3>
      <h4>Existing users:</h4>
      <!--Sidebar content-->
      <ul class="nav nav-list bs-docs-sidenav">
	<?php echo $viewUserList; ?>
      </ul>
      <ul class="nav nav-list bs-docs-sidenav">
        <li><a href="users.php?create" class="btn btn-large btn-primary"><i class="fa fa-user"></i> Create a new user</a></li>
      </ul>
    </div>
    <div class="span9">
      <!--Body content-->
        <?php require_once("template/".$loadFormUsers); ?>
    </div>
  </div>

  <?php require_once("template/footer.tpl"); ?>

</div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.0.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>
