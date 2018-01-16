<?php
	require_once __DIR__.'/../_puff/sitewide.php';
	$Page['Type']  = 'Admin';
	$Page['Title'] = 'Change a Users Details';

	if ( $Sitewide['Authenticated']['Role'] != 'Admin' ) {
		header('Location: '.$Sitewide['Settings']['Site Root'].'log_in.php?redirect='.urlencode($Sitewide['Request']['Path']), true, 302);
		exit;
	}

	require_once $Sitewide['Templates']['Header'];
	$Username = Puff_Member_Sanitize_Username($_GET['username']);

	if (
		isset($_POST['departments']) ||
		isset($_POST['role']) ||
		isset($_POST['name']) ||
		isset($_POST['email']) ||
		isset($_POST['phone'])
	) {
		if ( isset($_POST['departments']) ) {
			$Departments = json_encode($_POST['departments']);
			Puff_Member_Key_Update($Sitewide['Database']['Connection'], $_GET['username'], 'Department', $Departments);
		}
		if ( isset($_POST['role']) ) {
			Puff_Member_Key_Update($Sitewide['Database']['Connection'], $_GET['username'], 'Role', htmlentities($_POST['role'], ENT_QUOTES, 'UTF-8'));
		}
		if ( isset($_POST['name']) ) {
			Puff_Member_Key_Update($Sitewide['Database']['Connection'], $_GET['username'], 'Name', htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8'));
		}
		if ( isset($_POST['email']) ) {
			Puff_Member_Key_Update($Sitewide['Database']['Connection'], $_GET['username'], 'EMail', htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8'));
		}
		if ( isset($_POST['phone']) ) {
			Puff_Member_Key_Update($Sitewide['Database']['Connection'], $_GET['username'], 'Phone', htmlentities($_POST['phone'], ENT_QUOTES, 'UTF-8'));
		}
		$Name = Puff_Member_Key_Value($Sitewide['Database']['Connection'], $_GET['username'], 'Name');
		$EMail = Puff_Member_Key_Value($Sitewide['Database']['Connection'], $_GET['username'], 'EMail');
		$Phone = Puff_Member_Key_Value($Sitewide['Database']['Connection'], $_GET['username'], 'Phone');
		// Update Tickets
		$SQL = 'UPDATE
	`Tickets`
SET
    `Name`=\''.$Name.'\',
    `EMail`=\''.$EMail.'\',
    `Phone`=\''.$Phone.'\'
WHERE
	`Username` = \''.$_GET['username'].'\';';
		mysqli_query($Sitewide['Database']['Connection'], $SQL);
		echo '<h2 class="color-nephritis">The details for user '.$Username.' have been changed.</h2>';
		echo '<button class="navlink admin" onclick="window.location.href=\''.$Sitewide['Settings']['Site Root'].'admin/members.php\'"><i class="fa fa-arrow-right"></i> Continue</button>';
	} else {
?>

<h2>Change the Details for <?php echo $Username; ?></h2>
<form method="post">
<h3><i class="fa fa-users"></i> Departments</h3>
<?php
	$Departments = json_decode(html_entity_decode(Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Username, 'Department'), ENT_QUOTES, 'UTF-8'));
	$SQL = 'SELECT * FROM `Departments` WHERE `Active`=\'1\' ORDER BY `Order` DESC, `Department` ASC;';
	$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
	while ( $Department = mysqli_fetch_assoc($Result) ) {
		$Department = $Department['Department'];
	  echo '<input type="checkbox" name="departments[]" value="'.$Department.'" id="departments-'.$Department.'"';
		if ( in_array($Department, $Departments) ) {
			echo ' checked';
		}
		echo '> <label for="departments-'.$Department.'">'.$Department.'</option><br>';
	}
?>
<h3><i class="fa fa-users"></i> Role</h3>
<select name="role">
	<option value="User"
		<?php if ( Puff_Member_Key_Value($Sitewide['Database']['Connection'], $_GET['username'], 'Role') == 'User' ) { echo 'selected="selected"'; } ?>
	>User</option>
	<option value="Manager"
		<?php if ( Puff_Member_Key_Value($Sitewide['Database']['Connection'], $_GET['username'], 'Role') == 'Manager' ) { echo 'selected="selected"'; } ?>
	>Manager</option>
	<option value="Admin"
		<?php if ( Puff_Member_Key_Value($Sitewide['Database']['Connection'], $_GET['username'], 'Role') == 'Admin' ) { echo 'selected="selected"'; } ?>
	>Admin</option>
</select>
<h3><i class="fa fa-user"></i> Name</h3>
<input type="text"    name="name"    placeholder="John Smith" value="<?php echo Puff_Member_Key_Value($Sitewide['Database']['Connection'], $_GET['username'], 'Name'); ?>">
<h3><i class="fa fa-envelope"></i> EMail</h3>
<input type="email"    name="email"    placeholder="john.smith@banhampoultryuk.com" value="<?php echo Puff_Member_Key_Value($Sitewide['Database']['Connection'], $_GET['username'], 'EMail'); ?>">
<h3><i class="fa fa-phone"></i> Phone</h3>
<input type="number"   name="phone"    placeholder="123" min="200" max="99999999999" value="<?php echo Puff_Member_Key_Value($Sitewide['Database']['Connection'], $_GET['username'], 'Phone'); ?>">
<input type="submit"   value="Change">
</form>

<?php
	}
	require_once $Sitewide['Templates']['Footer'];
