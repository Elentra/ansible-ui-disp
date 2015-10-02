<?php if(isset($_SESSION['name']) and isset($_SESSION['key'])) { ?>
<?php if($_SESSION['name'] != "") { ?>
                        <div class="pull-right">
                            <ul class="nav">
                                <li class="active"><a href="users.php?uid=<?php echo $_SESSION['id']; ?>">Welcome, <b><?php echo $_SESSION['name']; ?></b></a></li>
                                <li><a href="<?php echo $config['system_root']; ?>?logout">Sign Out</a></li>
                            </ul>
                        </div>
<?php } ?>
<?php } else { ?>
                        <form method="post" class="navbar-form pull-right">
                            <input class="span2" type="text" placeholder="Login" name="login">
                            <input class="span2" type="password" placeholder="Password" name="password">
                            <button type="submit" class="btn">Sign in</button>
                        </form>

<?php } ?>
