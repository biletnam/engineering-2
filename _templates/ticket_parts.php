<div class="breaker"></div>
<hr>
<div class="breaker"></div>
<div class="grid">
	<h3>Parts</h3>
	<h4 class="dont-print">Current Parts</h4>
	<table class="tablesorter">
		<thead>
			<tr class="clickable">
				<th>STRC_CODE</th>
				<th>STRC_CODE2</th>
				<th>STRC_DESC</th>
				<th>Quantity</th>
				<th class="dont-print">Remove</th>
			</tr>
		</thead>
		<tbody id="current-parts">
			<?php
				$SQL = 'SELECT * FROM `Parts` WHERE `Ticket ID` = \''.$Ticket_ID.'\';';
				$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
				if ( !mysqli_num_rows($Result) ) {
					echo '<tr class="no-parts-currently-added"><td>No parts currently added.</td></tr>';
				}
				while ( $Part = mysqli_fetch_assoc($Result) ) {
					echo '<tr class="clickable">
						<td>'.$Part['STRC_CODE'].'</td>
						<td>'.$Part['STRC_CODE2'].'</td>
						<td>'.$Part['STRC_DESC'].'</td>
						<td>
							<form class="update-part">
								<input type="hidden" name="ticket" value="'.$Ticket_ID.'">
								<input type="hidden" name="STRC_CODE" value="'.$Part['STRC_CODE'].'">
								<input type="hidden" name="STRC_CODE2" value="'.$Part['STRC_CODE2'].'">
								<input type="number" name="quantity" value="'.$Part['Quantity'].'">
								<input type="submit" value="Update" class="dont-print">
							</form>
						</td>
						<td class="dont-print">
							<form class="remove-part">
								<input type="hidden" name="ticket" value="'.$Ticket_ID.'">
								<input type="hidden" name="STRC_CODE" value="'.$Part['STRC_CODE'].'">
								<input type="hidden" name="STRC_CODE2" value="'.$Part['STRC_CODE2'].'">
								<input type="submit" value="Remove">
							</form>
						</td>
					</tr>';
				}
			?>
		</tbody>
	</table>
	<div id="parts-form-container"  class="dont-print">
		<h4>Search for Parts</h4>
		<form id="parts-form">
			<input type="hidden" name="ticket" value="<?php echo $Ticket_ID; ?>">
			<input type="text" name="query" placeholder="Bin Location or Description" style="width:50%">
			<button type="submit"><i class="fa fa-search"></i> Search</button>
		</form>
		<table class="tablesorter" style="display:none;">
			<thead>
				<tr class="clickable">
					<th>STRC_CODE</th>
					<th>STRC_CODE2</th>
					<th>STRC_DESC</th>
					<th>Add</th>
				</tr>
			</thead>
			<tbody id="parts-list">
				<td>No search completed.</td>
			</tbody>
		</table>
	</div>
</div>
