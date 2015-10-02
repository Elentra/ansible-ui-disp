<div class="well">
	<h1>User management</h1>
	<table class="table table-striped table-hover">
		<th class="bg-color-gray" colspan="2"><span class="semi-bold"><h1>User #<?php echo $showUserId; ?></h1></span></th>
		<tr>
			<td><h3>Auth name (Login):</h3></td>
			<td><h3><strong><?php echo $showUserLogin; ?></strong></h3><td>
		</tr>
		<tr>
			<td><h3>Full name:</h3></td>
			<td><h3><strong><?php echo $showUserName; ?></strong></h3></td>
		</tr>
		<tr>
			<td><h3>Access class:</h3></td>
			<td><h3><strong><?php echo $showUserGroup; ?></strong></h3></td>
		</tr>
	</table>
	<a href="users.php?modifyID=<?php echo $showUserId; ?>" class="btn btn-warning"><i class="fa fa-times"></i> <strong>Change user <i><?php echo $showUserLogin; ?></i></strong></a>
<?php if($showUserId != $_SESSION['id']) { ?>
	<a href="users.php?deleteID=<?php echo $showUserId; ?>" class="btn btn-danger"><i class="fa fa-times"></i> <strong>Delete user <i><?php echo $showUserLogin; ?></i></strong></a>
<?php } else { ?>
	<button href="" class="btn btn-danger disabled"><i class="fa fa-times"></i> You cannot delete yourself</i></button>
<?php } ?>
</div>
