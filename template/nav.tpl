                        <ul class="nav">
                            <li class="<?php echo $viewIndexHover; ?>"><a href="index.php">Index</a></li>
<?php if(isset($_SESSION['name']) and isset($_SESSION['key'])) { ?>
                            <li class="<?php echo $viewDashboardHover; ?>"><a href="dashboard.php">Dashboard</a></li>
                            <li class="<?php echo $viewHostsHover; ?>"><a href="hosts.php">Hosts</a></li>
                            <li class="<?php echo $viewPlaybooksHover; ?>"><a href="playbooks.php">Playbooks</a></li>
                             <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administration <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class="nav-header">Maintenance</li>
                                    <li><a href="#">System Users</a></li>
                                    <li><a href="#">Interface Configuration</a></li>
                                    <li><a href="#">Info</a></li>
                                    <!--<li class="divider"></li>
                                    <li class="nav-header">Nav header</li>
                                    <li><a href="#">Separated link</a></li>
                                    <li><a href="#">One more separated link</a></li>-->
                                </ul>
                            </li>
<?php } ?>
                        </ul>
