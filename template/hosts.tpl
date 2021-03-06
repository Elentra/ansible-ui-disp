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
      <h3>Groups</h3>
      <h4>Pick a playbook</h4>
      <!--Sidebar content-->
      <ul class="nav nav-list bs-docs-sidenav">
        <?php echo $viewPlaybooksList; ?>
      </ul>
      <h4><br/>Pick a host bundle</h4>
      <ul class="nav nav-list bs-docs-sidenav">
        <?php echo $viewBundleList; ?>
      </ul>
      <h4><br/>Pick a group</h4>
      <ul class="nav nav-list bs-docs-sidenav">
        <?php echo $viewGroupList; ?>
      </ul>
    </div>
    <div class="span9">
      <h2><?php echo $projectName ?></h2>
      <!--Body content-->
<?php if(isset($projectHostList)) { ?>
      <h3><strong>Host group:</strong> <?php echo $bundleActive ?></h3>
     <table class="table table-striped table-bordered">
        <caption>Hosts in current group</caption>
        <thead>
          <tr>
            <th>Host</th>
            <th>IP</th>
          </tr>
        </thead>
        <tbody>
<?php foreach($projectHostList as $projectHost) { ?>
          <tr>
            <td><?php echo $projectHost ?></td>
            <td></td>
          </tr>
<?php } /* end-enum */ } /* end-if */ ?>
        </tbody>
     </table>
<!--
      <table class="table table-striped table-bordered">
        <caption>Last execution report</caption>
        <thead>
          <tr>
            <th>Task</th>
            <th>Last execution status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Add NGinx repository</td>
            <td><span class="label label-warning">Unchanged</span></td>
          </tr>
          <tr>
            <td>Install NGinx</td>
            <td><span class="label label-warning">Unchanged</span></td>
          </tr>
          <tr>
            <td>Install PHP-FPM</td>
            <td><span class="label label-warning">Unchanged</span></td>
          </tr>
          <tr>
            <td>Upload NGginx configuration file</td>
            <td><span class="label label-success">Changed</span></td>
          </tr>
          <tr>
            <td>Upload PHP-FPM configuration file</td>
            <td><span class="label label-warning">Unchanged</span></td>
          </tr>
          <tr>
            <td>Upload application</td>
            <td><span class="label label-success">Changed</span></td>
          </tr>
          <tr>
            <td>Upload application configuration</td>
            <td><span class="label label-warning">Unchanged</span></td>
          </tr>
          <tr>
            <td>Restart PHP-FPM</td>
            <td><span class="label label-success">Changed</span></td>
          </tr>
          <tr>
            <td>Restart NGinx</td>
            <td><span class="label label-success">Changed</span></td>
          </tr>
          <tr>
            <td>Open firewall (80)</td>
            <td><span class="label label-warning">Unchanged</span></td>
          </tr>
        </tbody>
      </table>

      <h3>Facter</h3>
      <div id="host-facter"></div>
    </div>-->
  </div>

  <?php require_once("template/footer.tpl"); ?>
</div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.0.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script src="http://mbraak.github.com/jqTree/tree.jquery.js"></script>
        <script>
          var data = [
              {
                  label: 'node1',
                  children: [
                      {
                          label: 'child1',
                          children: [
                              { label: 'childA',

                              },
                              { label: 'childB' }
                          ]
                      },
                      { label: 'child2' }
                  ]
              },
              {
                  label: 'node2',
                  children: [
                      { label: 'child3' }
                  ]
              }
          ];

          $('#host-facter').tree({
              data: data,
              autoOpen:0
          });
        </script>

        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>
