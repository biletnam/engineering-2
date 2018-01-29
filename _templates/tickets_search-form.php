<form method="GET">
	<input type="checkbox"<?php if ( $Status['New'] ) echo ' checked'; ?> class="appearance" id="checkbox-status-new" name="status-new"><label for="checkbox-status-new">New</label>
	<input type="checkbox"<?php if ( $Status['Waiting'] ) echo ' checked'; ?>  class="appearance" id="checkbox-status-waiting" name="status-waiting"><label for="checkbox-status-waiting">Waiting</label>
	<input type="checkbox"<?php if ( $Status['In Progress'] ) echo ' checked'; ?>  class="appearance" id="checkbox-status-in-progress" name="status-in-progress"><label for="checkbox-status-in-progress">In Progress</label>
	<input type="checkbox"<?php if ( $Status['Complete'] ) echo ' checked'; ?>  class="appearance" id="checkbox-status-complete" name="status-complete"><label for="checkbox-status-complete">Complete</label>
	<input type="checkbox"<?php if ( $Status['Signed Off'] ) echo ' checked'; ?>  class="appearance" id="checkbox-status-signed-off" name="status-signed-off"><label for="checkbox-status-signed-off">Signed Off</label>
	<input type="checkbox"<?php if ( $Status['Cancelled'] ) echo ' checked'; ?>  class="appearance" id="checkbox-status-cancelled" name="status-cancelled"><label for="checkbox-status-cancelled">Cancelled</label>
	&emsp;
	<select name="department" class="whole" required>
		<option value="*"<?php if ( $SelectedDepartment == '*' || $SelectedDepartment == 'Engineering' ) echo ' selected="selected"'; ?>>All Departments</option>
		<?php
			$SQL = 'SELECT * FROM `Departments` WHERE `Active`=\'1\' ORDER BY `Order` DESC, `Department` ASC;';
			$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
			$DepartmentSelected = false;
			while ( $Department = mysqli_fetch_assoc($Result) ) {
				$Selected = false;
				if (
					$SelectedDepartment == $Department['Department'] &&
					$Department['Department'] != 'Engineering'
				) {
					$Selected = ' selected="selected"';
					$DepartmentSelected = true;
				}
				echo '<option value="'.$Department['Department'].'"'.$Selected .'>'.$Department['Department'].'</option>';
			}
			echo '<option value="Other"';
			if ( $SelectedDepartment == 'Other' ) {
				echo ' selected="selected"';
				$DepartmentSelected = true;
			}
			echo '>Other</option>';
		?>
	</select>
	&emsp;
	<select name="line" class="whole">
		<?php
			$LineSelected = false;
			if ( !$DepartmentSelected ) {
				echo '<option value="*" disabled selected>All Lines</option>';
			} else {
				echo '<option value="*" selected>All Lines</option>';
				$SQL = 'SELECT * FROM `Lines` WHERE `Active`=\'1\' AND `Department`=\''.$SelectedDepartment.'\' ORDER BY `Order` DESC, `Department` ASC, `Line` ASC;';
				$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
				while ( $Line = mysqli_fetch_assoc($Result) ) {
					$Selected = false;
					if ( $SelectedLine == $Line['Line'] ) {
						$Selected = ' selected="selected"';
						$LineSelected = true;
					}
					echo '<option value="'.$Line['Line'].'"'.$Selected.'>'.$Line['Line'].'</option>';
				}
				echo '<option value="Other"';
				if ( $SelectedLine == 'Other' ) {
					echo ' selected="selected"';
					$LineSelected = true;
				}
				echo '>Other</option>';
			}
		?>
	</select>
	&emsp;
	<select name="machine" class="whole">
		<?php
			if ( !$LineSelected ) {
				echo '<option value="*" disabled selected>All Machines</option>';
			} else {
				echo '<option value="*" selected>All Machines</option>';
				$SQL = 'SELECT * FROM `Machines` WHERE `Active`=\'1\' AND `Department`=\''.$SelectedDepartment.'\' AND `Line`=\''.$SelectedLine.'\' ORDER BY `Order` DESC, `Department` ASC, `Line` ASC, `Machine` ASC, `AssetTag` ASC;';
				$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
				while ( $Machine = mysqli_fetch_assoc($Result) ) {
					if ( $SelectedMachine == $Machine['AssetTag'] ) {
						$Selected = ' selected="selected"';
					}
					echo '<option value="'.$Machine['AssetTag'].'"'.$Selected.'>'.$Machine['Machine'].'</option>';
				}
				echo '<option value="Other"';
				if ( $SelectedMachine == 'Other' ) {
					echo ' selected="selected"';
				}
				echo '>Other</option>';
			}
		?>
	</select>
	&emsp;
	<select name="assignee" class="whole">
		<?php
			echo '<option value="*" selected>Any Assignee</option>';
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
				if ( $SelectedAssignee == $Engineer['Username'] ) {
					$Selected = ' selected="selected"';
				} else {
					$Selected = false;
				}
				echo '<option value="'.$Engineer['Username'].'"'.$Selected.'>'.$Engineer['Name'].'</option>';
			}
		?>
	</select>
	&emsp;
	<input name="soft-search" type="text" placeholder="Machine details" value="<?php echo $SoftSearch; ?>">
	&emsp;
	<button type="submit"><i class="fa fa-search"></i> Search</button>
</form>