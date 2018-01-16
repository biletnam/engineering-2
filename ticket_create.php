<?php
	require_once __DIR__.'/_puff/sitewide.php';
	$Page['Type']  = 'Page';
	$Page['Title'] = 'Create a Ticket';
	require_once $Sitewide['Templates']['Header'];
	if ( !empty($Sitewide['Authenticated']) ) {
		$Runonce = Puff_Runonce_Create($Sitewide['Database']['Connection'], $Sitewide['Authenticated']['Session']);
	} else {
		$Runonce = Puff_Runonce_Create($Sitewide['Database']['Connection']);
	}
?>

<h2>Create a Ticket</h2>

<form enctype="multipart/form-data" method="POST" class="grid" action="api/ticket_create.php">
	<input type="hidden" name="runonce"     value="<?php echo $Runonce['Runonce']; ?>">

<div class="quarter user">
	<h2><span class="faded">Step 1.</span> Who</h2>
<?php
	if ( !empty($Sitewide['Authenticated']) ) {
?>
	<h3><i class="fa fa-user"></i> Name</h3>
	<input type="text"   class="whole" name="name"        value="<?php echo $Sitewide['Authenticated']['Name']; ?>" disabled>
	<h3><i class="fa fa-envelope"></i> EMail</h3>
	<input type="text"   class="whole" name="email"       value="<?php echo $Sitewide['Authenticated']['EMail']; ?>"  disabled>
	<h3><i class="fa fa-phone"></i> Phone</h3>
	<input type="text"   class="whole" name="phone"       value="<?php echo $Sitewide['Authenticated']['Phone']; ?>"  disabled>
<?php
	} else {
?>
	<p>You should <a href="<?php echo $Sitewide['Settings']['Site Root'].'api/log_out.php?redirect='.urlencode($Sitewide['Request']['Full']); ?>">log in</a> to auto-fill these fields.</p>
	<h3><i class="fa fa-user"></i> Name</h3>
	<input type="text"   class="whole" name="name"        placeholder="John Smith" required>
	<h3><i class="fa fa-envelope"></i> EMail</h3>
	<input type="text"   class="whole" name="email"       placeholder="john.smith@banhampoultryuk.com">
	<h3><i class="fa fa-phone"></i> Phone</h3>
	<input type="text"   class="whole" name="phone"       placeholder="118">
<?php
	}
?>
</div>

<div class="half ticket">
	<h2><span class="faded">Step 2.</span> What</h2>
	<h3>Title</h3>
	<input type="text" class="whole" name="title"       placeholder="Breifly describe your engineering issue." required>
	<h3>Description</h3>
	<textarea          class="whole" name="description" placeholder="If necessary, describe your problem in more detail." rows="7"></textarea>
	<input type="hidden" name="MAX_FILE_SIZE" value="64000000" />
	<input type="file" name="upload" />
	<button type="submit" class="float-right"><i class="fa fa-plus"></i> Create Ticket</button>
</div>

<div class="quarter user">
	<h2><span class="faded">Step 3.</span> Where</h2>
	<h3>Department</h3>
	<select name="department" class="whole" required>
		<option value="" disabled selected>Please select a department.</option>
		<?php
			$SQL = 'SELECT * FROM `Departments` WHERE `Active`=\'1\' ORDER BY `Order` DESC, `Department` ASC;';
			$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
			while ( $Department = mysqli_fetch_assoc($Result) ) {
				$Selected = false;
				if (
					count($Sitewide['Authenticated']['Departments']) == 1 &&
					$Sitewide['Authenticated']['Department'] == $Department['Department']
				) {
					$Selected = ' selected="selected"';
				}
				echo '<option value="'.$Department['Department'].'"'.$Selected .'>'.$Department['Department'].'</option>';
			}
		?>
		<option value="Other">Other</option>
	</select>
	<h3>Line / Room</h3>
	<select name="line" class="whole" required>
		<?php
			if ( !$Sitewide['Authenticated']['Department'] ) {
				echo '<option value="" disabled selected>Please select a department first.</option>';
			} else {
				echo '<option value="" disabled selected>Please select a line.</option>';
				$SQL = 'SELECT * FROM `Lines` WHERE `Active`=\'1\' AND `Department`=\''.$Sitewide['Authenticated']['Department'].'\' ORDER BY `Order` DESC, `Department` ASC, `Line` ASC;';
				$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
				while ( $Department = mysqli_fetch_assoc($Result) ) {
					echo '<option value="'.$Department['Line'].'">'.$Department['Line'].'</option>';
				}
				echo '<option value="Other">Other</option>';
			}
		?>
	</select>
	<h3>Machine</h3>
	<select name="machine" class="whole" required>
		<option value="" disabled selected>Please select a line first.</option>
	</select>
</div>

<div class="clear"></div>
<br>

</form>

<?php
	require_once $Sitewide['Templates']['Footer'];
