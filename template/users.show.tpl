<?php if($modifyUser) { ?>
	<div class="well">
		<h1>User management</h1>
		<form method="post" action="users.php">
		<table class="table table-striped table-hover">
			<th class="bg-color-gray" colspan="2"><span class="semi-bold"><h1>User #<?php echo $showUserId; ?></h1></span></th>
			<tr>
				<td>Authorization name:</td>
				<td><strong><?php echo $showUserLogin; ?></strong><td>
			</tr>
			<tr>
				<td>Description:</h3><br/><small>* Full name</small></td>
				<td><input type="text" name="name" value="<?php echo $showUserName; ?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<td>Group:</td>
				<td><label class="select">
				<select name="class">
					<option <?php if($showUserGroup == "Administrators") echo "selected"; ?> value="Administrators">Administrators</option>
					<option <?php if($showUserGroup == "Managers") echo "selected"; ?> value="Managers">Managers</option>
					<option <?php if($showUserGroup == "Observers") echo "selected"; ?> value="Observers">Observers</option>>
				</select>
				</label></td>
			</tr>
			<tr>
				<td>New password:<br/><small>Leave blank if you don't want to change it</small></td>
				<td><input type="text" name="new-password-one" value="" autocomplete="off" /></td>
			</tr>
			<tr>
				<td>Retype password:<br/><small>Retype password to confirm it</small></td>
				<td><input type="text" name="new-password-two" name="name" value="" autocomplete="off" /></td>
			</tr>
		</table>
		<input type="hidden" name="modifyuser" value="<?php echo $identifyCode; ?>">
		<input type="hidden" name="uid" value="<?php echo $showUserId; ?>">
		<input type="hidden" name="uid_name" value="<?php echo $showUserLogin; ?>">
		<button type="submit" class="btn btn-primary">
			Done
		</button>
		<button type="button" class="btn btn-default" onclick="window.history.back();">
			Back
		</button>
		</form>
	</div>
<?php } else { ?>
	<div class="well">
		<h1>User management</h1>
		<table class="table table-striped table-hover">
			<th class="bg-color-gray" colspan="2"><span class="semi-bold"><h1>User #<?php echo $showUserId; ?></h1></span></th>
			<tr>
				<td>Authorization name:</td>
				<td><strong><?php echo $showUserLogin; ?></strong><td>
			</tr>
			<tr>
				<td>Description:<br/><small>Full name or readable name</small></td>
				<td><strong><?php echo $showUserName; ?></strong></td>
			</tr>
			<tr>
				<td>Group:</td>
				<td><strong><?php echo $showUserGroup; ?></strong></td>
			</tr>
		</table>
		<a href="users.php?modifyID=<?php echo $showUserId; ?>" class="btn btn-warning"><i class="fa fa-times"></i> <strong>Change user <i><?php echo $showUserLogin; ?></i></strong></a>
	<?php if($showUserId != $_SESSION['id']) { ?>
		<a href="users.php?deleteID=<?php echo $showUserId; ?>" class="btn btn-danger"><i class="fa fa-times"></i> <strong>Delete user <i><?php echo $showUserLogin; ?></i></strong></a>
	<?php } else { ?>
		<button href="" class="btn btn-danger disabled"><i class="fa fa-times"></i> You cannot do this</i></button>
	<?php } ?>
	</div>
<?php } ?>
