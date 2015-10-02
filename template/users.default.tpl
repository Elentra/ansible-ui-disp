<?php ?>
<div class="well">
	<h1>User management</h1>
</div>

<?php if($spawnWarning) { ?>
<div class="alert alert-warning fade in">
	<button class="close" data-dismiss="alert">
		×
	</button>
	<i class="fa-fw fa fa-warning"></i>
	<strong>Warning</strong> <?php echo $spawnWarningText; ?>
</div>
<?php } ?>
<?php if($spawnSuccess) { ?>
<div class="alert alert-success fade in">
	<button class="close" data-dismiss="alert">
		×
	</button>
	<i class="fa-fw fa fa-check"></i>
	<strong>Success</strong> <?php echo $spawnSuccessText; ?>
</div>
<?php } ?>
<?php if($spawnError) { ?>
<div class="alert alert-danger fade in">
	<button class="close" data-dismiss="alert">
		×
	</button>
	<i class="fa-fw fa fa-times"></i>
	<strong>Error!</strong> <?php echo $spawnErrorText; ?>
</div>
<?php } ?>

<div class="container">
	<h1>You can select a user to manage or create a new one  <span></span></h1>
	<p></p>
	<p><h3>No users is currently selected</h3></p>
	<p><h5>Use navigational menu to select specific user</h5></p>
</div>
