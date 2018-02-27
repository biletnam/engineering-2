<?php
	require_once __DIR__.'/../_puff/sitewide.php';
	$Page['Type']  = 'Admin';
	$Page['Title'] = 'Change a Machines Details';
	$AssetTag = htmlentities($_GET['assettag'], ENT_QUOTES, 'UTF-8');
	$SQL = 'SELECT * FROM `Machines` WHERE `AssetTag`=\''.$AssetTag.'\';';
	$Machine = mysqli_fetch_once($Sitewide['Database']['Connection'], $SQL);

	if ( !isEngManager($Sitewide['Authenticated']) ) {
		header('Location: '.$Sitewide['Settings']['Site Root'].'log_in.php?redirect='.urlencode($Sitewide['Request']['Path']), true, 302);
		exit;
	}

	require_once $Sitewide['Templates']['Header'];

	if (
		isset($_POST['machine']) ||
		isset($_POST['description']) ||
		isset($_POST['make']) ||
		isset($_POST['model']) ||
		isset($_POST['serialno']) ||
		isset($_POST['department']) ||
		isset($_POST['line']) ||
		isset($_POST['assettag'])
	) {

		// Step 1. Sanatize inputs
		$Machine     = htmlentities($_POST['machine'],     ENT_QUOTES, 'UTF-8');
		$Description = htmlentities($_POST['description'], ENT_QUOTES, 'UTF-8');
		$Make        = htmlentities($_POST['make'],        ENT_QUOTES, 'UTF-8');
		$Model       = htmlentities($_POST['model'],       ENT_QUOTES, 'UTF-8');
		$SerialNo    = htmlentities($_POST['serialno'],    ENT_QUOTES, 'UTF-8');
		$Department  = htmlentities($_POST['department'],  ENT_QUOTES, 'UTF-8');
		$Line        = htmlentities($_POST['line'],        ENT_QUOTES, 'UTF-8');
		$AssetTag_Original = htmlentities($_POST['assettag_original'], ENT_QUOTES, 'UTF-8');
		$AssetTag_New = htmlentities($_POST['assettag_new'], ENT_QUOTES, 'UTF-8');
		
		// Step 2. Update Machine
		$SQL = '
UPDATE
	`Machines`
SET
    `Machine`=\''.$Machine.'\',
    `Description`=\''.$Description.'\',
    `Make`=\''.$Make.'\',
    `Model`=\''.$Model.'\',
    `SerialNo`=\''.$SerialNo.'\',
    `Department`=\''.$Department.'\',
    `Line`=\''.$Line.'\',
    `AssetTag`=\''.$AssetTag_New.'\'
WHERE
	`AssetTag` = \''.$AssetTag_Original.'\';';
		$Step2Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
		$Step2Affected = mysqli_affected_rows($Sitewide['Database']['Connection']);
		
		// Step 3. Update linked Tickets
		if (
			$Step2Result &&
			$AssetTag_Original != $AssetTag_New
		) {
			$SQL = '
UPDATE
	`Tickets`
SET
    `AssetTag`=\''.$AssetTag_New.'\'
WHERE
	`AssetTag` = \''.$AssetTag_Original.'\';';
			$Step3Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
			$Step3Affected = mysqli_affected_rows($Sitewide['Database']['Connection']);
		}
		
		// Step 4. Echo results
		echo '<h2 class="color-nephritis">The details for the machine have been changed.</h2>';
		
		echo '<pre>
Updating the machines details...
'.json_encode($Step2Result).'
'.$Step2Affected.' machines details were updated.
';
		
		if (
			$Step2Result &&
			$AssetTag_Original != $AssetTag_New
		) {
			echo '
Updating linked tickets...
'.json_encode($Step3Result).'
'.$Step3Affected.' linked tickets were updated.';
		} else {
			echo '
No need to update linked tickets.';
		}
		
		echo '
</pre>';
		
		echo '<button class="navlink admin" onclick="window.location.href=\'machine_details.php?assettag='.$AssetTag_New.'\'"><i class="fa fa-arrow-right"></i> Continue</button>';

	} else {
?>

<h2>Change the Details for <?php echo $AssetTag; ?></h2>

<form method="post" class="grid">

	<h3>Machine</h3>
	<input type="text" class="whole medium-half" name="machine" value="<?php echo $Machine['Machine']; ?>">

	<h3>Description</h3>
	<input type="text" class="whole medium-half" name="description" value="<?php echo $Machine['Description']; ?>">

	<h3>Make</h3>
	<input type="text" class="whole medium-half" name="make" value="<?php echo $Machine['Make']; ?>">

	<h3>Model</h3>
	<input type="text" class="whole medium-half" name="model" value="<?php echo $Machine['Model']; ?>">

	<h3>Serial Number</h3>
	<input type="text" class="whole medium-half" name="serialno" value="<?php echo $Machine['SerialNo']; ?>">

	<h3>Department</h3>
	<select name="department" class="whole medium-half" required>
		<?php
			
			$SQL = 'SELECT * FROM `Departments` WHERE `Active`=\'1\' ORDER BY `Order` ASC, `Department` ASC;';
			$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
			$DepartmentSelected = false;
			
			while ( $Department = mysqli_fetch_assoc($Result) ) {
				$Selected = false;
				if ( $Machine['Department'] == $Department['Department'] ) {
					$Selected = ' selected="selected"';
					$DepartmentSelected = true;
				}
				echo '<option value="'.$Department['Department'].'"'.$Selected .'>'.$Department['Department'].'</option>';
			}
			
			echo '<option value="Orphaned"';
			if ( !$DepartmentSelected ) {
				echo ' selected="selected"';
			}
			echo '>Orphaned</option>';
			
		?>
	</select>

	<h3>Line</h3>
	<select name="line" class="whole medium-half" required>
		<?php
			
			$SQL = 'SELECT * FROM `Lines` WHERE `Department`=\''.$Machine['Department'].'\' AND `Active`=\'1\' ORDER BY `Order` ASC, `Line` ASC;';
			$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
			$LineSelected = false;
			
			while ( $Line = mysqli_fetch_assoc($Result) ) {
				$Selected = false;
				if ( $Machine['Line'] == $Line['Line'] ) {
					$Selected = ' selected="selected"';
					$LineSelected = true;
				}
				echo '<option value="'.$Line['Line'].'"'.$Selected .'>'.$Line['Line'].'</option>';
			}
			
			echo '<option value="Orphaned"';
			if ( !$LineSelected ) {
				echo ' selected="selected"';
			}
			echo '>Orphaned</option>';
			
		?>
	</select>
	
	<div class="medium-third">
		<h3>AssetTag</h3>
		<p class="color-pomegranate">WARNING: This will affect all linked tickets.</p>
		<input type="hidden" name="assettag_original" value="<?php echo $Machine['AssetTag']; ?>" required>
	</div>
	<input type="text" class="whole medium-half" name="assettag_new" value="<?php echo $Machine['AssetTag']; ?>" required>

	<div class="clear"></div>
	<div class="medium-third"></div>
	<input type="submit" class="whole medium-half" value="Change">

</form>

<?php
	}
	require_once $Sitewide['Templates']['Footer'];
