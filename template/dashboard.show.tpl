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
          <tr>
            <td>mailtest.corp.servdesk.ru</td>
            <td>17</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td>mailtest2.corp.servdesk.ru</td>
            <td>17</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
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
            <td rowspan="4">Frontend</td>
            <td>www1.ansible.org</td>
            <td>192.168.1.1</td>
            <td><span class="badge badge-success">Ok</span></td>
          </tr>
          <tr>
            <td>www2.ansible.org</td>
            <td>192.168.1.2</td>
            <td><span class="badge badge-important">Error</span></td>
          </tr>
          <tr>
            <td>www3.ansible.org</td>
            <td>192.168.1.3</td>
            <td><span class="badge badge-success">Ok</span></td>
          </tr>
          <tr>
            <td>www4.ansible.org</td>
            <td>192.168.1.4</td>
            <td><span class="badge badge-success">Ok</span></td>
          </tr>

          <tr>
            <td rowspan="3">Database</td>
            <td>db-master1.ansible.org</td>
            <td>192.168.10.1</td>
            <td><span class="badge badge-success">Ok</span></td>
          </tr>
          <tr>
            <td>db-slave1.ansible.org</td>
            <td>192.168.10.2</td>
            <td><span class="badge badge-success">Ok</span></td>
          </tr>
          <tr>
            <td>db-slave2.ansible.org</td>
            <td>192.168.10.3</td>
            <td><span class="badge badge-success">Ok</span></td>
          </tr>

        </tbody>
      </table>
