<?php
	require_once __DIR__.'/_puff/sitewide.php';

	$Ticket_ID = Puff_Member_Sanitize_Username($_GET['id']);
	$SQL = 'SELECT * FROM `Tickets` JOIN `Statuses` ON `Tickets`.`Status` = `Statuses`.`Status` WHERE `Ticket`=\''.$Ticket_ID.'\';';
	$Ticket = mysqli_fetch_once($Sitewide['Database']['Connection'], $SQL);
	if ( !$Ticket ) {
		echo 'Error: Ticket not found.';
		exit;
	}
	$Ticket['Assigned'] = json_decode($Ticket['Assigned'], true);

	require_once $Sitewide['Puff']['Libs'].'Parsedown.php';
	require_once $Sitewide['Puff']['Libs'].'ParsedownExtra.php';
	$Extra = new ParsedownExtra();

	$Page['Type']  = 'Page';
	$Page['Title'] = 'Ticket #'.$Ticket_ID;

	if ( !empty($Sitewide['Authenticated']) ) {
		$Runonce = Puff_Runonce_Create($Sitewide['Database']['Connection'], $Sitewide['Authenticated']['Session']);
	} else {
		$Runonce = Puff_Runonce_Create($Sitewide['Database']['Connection']);
	}

	require_once $Sitewide['Templates']['Header'];
	require $Sitewide['Templates']['Root'].'ticket_details.php';
?>

<div class="grid">
	<div class="three-quarters comments">
		<div class="grid">
			<div class="third"></div>
			<div class="two-thirds">
				<h2>Comments</h2>
			</div>
		</div>
		<?php
			$SQL = "SELECT
	`Comments`.`ID`,
	`Comments`.`Timestamp`,
	`Comments`.`Comment`,
	`KeyValues`.`Username`,
	`KeyValues`.`Value` AS `Name`
FROM `Comments`
JOIN `KeyValues`
	ON `Comments`.`Username` = `KeyValues`.`Username`
WHERE
	`Ticket`='".$Ticket_ID."' AND
	`KeyValues`.`Key` = 'Name'
ORDER BY `Timestamp` ASC";
			$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
			if ( !mysqli_num_rows($Result) || !$Result ) {
				echo '
		<div class="grid">
			<div class="third"></div>
			<div class="two-thirds">
				<p class="faded">No Comments.</p>
			</div>
		</div>';
			}
			while ( $Comment = mysqli_fetch_assoc($Result) ) {
				echo '
					<div class="grid" id="comment-'.$Comment['ID'].'">
						<div class="third">
							<p><strong>'.$Comment['Name'].'</strong></p>
							<p><em>'.$Comment['Username'].'</em></p>
							<p><em>'.$Comment['Timestamp'].'</em></p>
						</div>
						<div class="two-thirds">
							'.$Extra->text($Comment['Comment']).'
						</div>
					</div>';
			}
		?>
		<div class="breaker"></div>
		<div class="grid">
			<div class="third"></div>
			<div class="two-thirds">
				<hr>
			</div>
		</div>
		<div class="breaker"></div>
		<div class="grid">
			<div class="third"></div>
			<div class="half">
				<?php
					if ( empty($Sitewide['Authenticated']) ) {
				?>
					<p>You should <a href="<?php echo $Sitewide['Settings']['Site Root'].'log_in.php?redirect='.urlencode($Sitewide['Request']['Full']); ?>">log in</a> to comment.</p>
				<?php
					} else {
				?>
					<form class="dont-print" enctype="multipart/form-data" action="api/comment_process.php" method="POST">
						<h3>Comment</h3>
						<input type="hidden" name="runonce" value="<?php echo $Runonce['Runonce']; ?>">
						<input type="hidden" name="ticket" value="<?php echo $Ticket_ID; ?>">
						<textarea class="whole" name="comment" placeholder="" rows="6"></textarea>
						<input type="hidden" name="MAX_FILE_SIZE" value="64000000" />
						<input type="file" name="upload" />
						<input type="submit" class="float-right" value="Comment">
					</form>
				<?php
					}
				?>
			</div>
		</div>
	</div>
	<div class="quarter timings" id="assignment">
		<h2>Assignment</h2>
		<form action="api/ticket_assign.php" method="POST" class="grid">
			<input type="hidden" name="ticket" value="<?php echo $Ticket_ID; ?>">
			<?php
				$SQL = "SELECT
	`Members`.`Username`,
	`Names`.`Value` AS `Name`
FROM `Members`
JOIN `KeyValues` AS `Departments`
	ON `Members`.`Username` = `Departments`.`Username`
JOIN `KeyValues` AS `Names`
	ON `Members`.`Username` = `Names`.`Username`
WHERE
	`Members`.`Active`='1' AND
	`Departments`.`Key`='Department' AND
	`Departments`.`Value` LIKE '%Engineering%' AND
	`Names`.`Key`='Name'
ORDER BY `Names`.`Value` ASC";
				$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
				while ( $Engineer = mysqli_fetch_assoc($Result) ) {
					echo '<div class="whole"><input type="checkbox" name="engineers[]" value="'.$Engineer['Username'].'" id="checkbox-assign-'.$Engineer['Username'].'"';
					if ( !empty($Ticket['Assigned']) && in_array($Engineer['Username'], $Ticket['Assigned']) ) {
						echo ' checked';
					}
					if ( !isEngManager($Sitewide['Authenticated']) ) {
						echo ' disabled';
					}
					echo '> <label for="checkbox-assign-'.$Engineer['Username'].'">'.$Engineer['Name'].'</label></div>';
				}
				if ( isEngManager($Sitewide['Authenticated']) ) {
					echo '<div class="dont-print whole">
							<input type="submit" value="Assign">
						</div>';
				}
			?>
		</form>
		<?php
			if ( isEngManager($Sitewide['Authenticated']) ) {
		?>
		<div class="breaker"></div>
		<hr class="dont-print">
		<div class="dont-print breaker"></div>
		<h2 class="dont-print">Change Status</h2>
		<form action="api/status_change.php" method="POST"  class="dont-print">
			<input type="hidden" name="ticket" value="<?php echo $Ticket_ID; ?>">
			<select name="status" required>
				<option value="no-change" selected>Select a Status.</option>
				<?php
					$SQL = 'SELECT * FROM `Statuses` ORDER BY `Statuses`.`Order` ASC;';
					$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
					while ( $Status = mysqli_fetch_assoc($Result) ) {
						if ( $Status['Status'] == 'Signed Off' ) {
							if (
								$Ticket['Status'] == 'Complete' &&
								$Status['Status'] != 'Complete' &&
								isEngManager($Sitewide['Authenticated'])
							) {
								echo '<option value="'.$Status['Status'].'">Change Status to '.$Status['Status'].'</option>';
							}
						} else if ( $Status['Status'] == 'Complete' ) {
							if (
								$Ticket['Status'] != $Status['Status'] &&
								isEngManager($Sitewide['Authenticated'])
							) {
								echo '<option value="'.$Status['Status'].'">Change Status to '.$Status['Status'].'</option>';
							}
						} else if ( $Ticket['Status'] != $Status['Status'] ) {
							echo '<option value="'.$Status['Status'].'">Change Status to '.$Status['Status'].'</option>';
						}
					}
				?>
			</select>
			<div class="js-target-sign-off"></div>
			<input class="dont-print" type="submit" value="Change">
		</form>
		<?php } ?>
	</div>
</div>

<?php
	if ( isEngManager($Sitewide['Authenticated']) ) {
		require $Sitewide['Templates']['Root'].'ticket_parts.php';
 	}
?>

<div class="clear"></div>

<?php
	require_once $Sitewide['Templates']['Footer'];
