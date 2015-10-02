<div class="well">
<h2>New User:</h2>
<form action="users.php" method="post">
<header>
	Please fill all the fields below
</header>

<fieldset>
	
	<section>
		<label class="label bg-color-blueDark">Enter new user's login</label>
		<label class="input">
			<input type="text" maxlength="30" autocomplete="off" name="login">
		</label>
		<div class="note">
			<span class="txt-color-blueDark">Please note, that the <strong>login</strong> name <strong>must</strong> be unique</span>
		</div>
	</section>
	<section>
		<label class="label bg-color-blueDark">Enter new user's name</label>
		<label class="input">
			<input type="text" maxlength="30" autocomplete="off" name="name">
		</label>
	</section>
	<section>
		<label class="label bg-color-blueDark">Enter new user's password</label>
		<label class="input">
			<input type="text" maxlength="30" autocomplete="off" name="password-one">
		</label>
	</section>
	<section>
		<label class="label bg-color-blueDark">Confirm password</label>
		<label class="input">
			<input type="text" maxlength="30" autocomplete="off" name="password-two">
		</label>
	</section>
		
</fieldset>

<fieldset>
	
	<section>
		<label class="label bg-color-blueDark">New user's group</label>
		<label class="select">
			<select name="class">
				<option value="Administrators">Administrators</option>
				<option value="Managers">Managers</option>
				<option value="Observers">Observers</option>>
			</select>
		</label>
	</section>

</fieldset>
<footer>
	<input type="hidden" name="newuser" value="">
	<button type="submit" class="btn btn-primary">
		Submit
	</button>
	<button type="button" class="btn btn-default" onclick="window.history.back();">
		Back
	</button>
</footer>
</form>
</div>
