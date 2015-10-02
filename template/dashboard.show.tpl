<?php ?>
      <div class="container">
        <p><h3>Task details</h3></p>
        <p>Task identifier <b>#<?php echo $taskProperty['name'];?></b></p>
        <p>Started at <b><?php echo $taskProperty['timestamp'];?></b></p>
        <p>Issued in playbook <a href="playbooks.php?pr=<?php echo $taskProperty['parent_id'];?>"><?php echo $Ansible->findProjectById($taskProperty['parent_id'])['name'] ?></a></b></p>
        <p></p>
        <p></p>
      </div>
      <div>
      <table class="table table-striped table-bordered">
        <caption><h4>Recap status</h4></caption>
        <thead>
          <tr>
            <th>Host</th>
            <th><span class="badge badge-success">Ok</span></th>
            <th><span class="badge badge-warning">Changed</span></th>
            <th><span class="badge badge-default">Unreachable</span></th>
            <th><span class="badge badge-important">Failed</span></th>
          </tr>
        </thead>
        <tbody>
	   <?php echo $viewStatusBlock; ?>
        </tbody>
      </table>
      </div>
      <table class="table table-striped table-bordered">
        <caption><h4>Hosts</h4></caption>
        <thead>
          <tr>
            <th>Group</th>
            <th>Host</th>
            <th>IP</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td rowspan="2">Group</td>
            <td>host1.domain.tld</td>
            <td>192.168.0.1</td>
            <td><span class="badge badge-success">Ok</span></td>
          </tr>
          <tr>
            <td>host1.domain.tld</td>
            <td>192.168.0.2</td>
            <td><span class="badge badge-important">Error</span></td>
          </tr>
          </tr>
        </tbody>
      </table>
