<h2>Ticket #<?php echo $Ticket_ID; ?> <?php echo '<span class="status-tag color-white background-'.$Ticket['Color'].'">'.$Ticket['Status'].'</span>'; ?></h2>
<div class="grid">

	<div class="quarter user">
		<h2>Contact</h2>
		<h3><i class="fa fa-user"></i> Username</h3>
		<p><?php echo $Ticket['Username']; ?></p>
		<h3><i class="fa fa-user"></i> Name</h3>
		<p><?php echo $Ticket['Name']; ?></p>
		<h3><i class="fa fa-envelope"></i> EMail</h3>
		<p><a href="mailto:<?php echo $Ticket['EMail']; ?>"><?php echo $Ticket['EMail']; ?></a></p>
		<h3><i class="fa fa-phone"></i> Phone</h3>
		<p><?php echo $Ticket['Phone']; ?></p>
	</div>

	<div class="half ticket">
		<h2>Ticket Description</h2>
		<h3><?php echo $Ticket['Title']; ?></h3>
		<?php echo $Extra->text($Ticket['Description']); ?>
	</div>

	<div class="quarter user">
		<h2>Location</h2>
		<h3>Department</h3>
		<p><a href="http://engineering.banhampoultryuk.com.local/tickets.php?status-new=on&status-waiting=on&status-in-progress=on&status-complete=on&status-signed-off=on&status-cancelled=on&department=<?php echo urlencode($Ticket['Department']); ?>"><?php echo $Ticket['Department']; ?></a></p>
		<h3>Line / Room</h3>
		<p><a href="http://engineering.banhampoultryuk.com.local/tickets.php?status-new=on&status-waiting=on&status-in-progress=on&status-complete=on&status-signed-off=on&status-cancelled=on&department=<?php echo urlencode($Ticket['Department']); ?>&line=<?php echo urlencode($Ticket['Line']); ?>"><?php echo $Ticket['Line']; ?></a></p>
		<h3>Machine</h3>
		<p><a href="http://engineering.banhampoultryuk.com.local/tickets.php?status-new=on&status-waiting=on&status-in-progress=on&status-complete=on&status-signed-off=on&status-cancelled=on&department=<?php echo urlencode($Ticket['Department']); ?>&line=<?php echo urlencode($Ticket['Line']); ?>&machine=<?php echo urlencode($Ticket['AssetTag']); ?>"><?php echo $Ticket['Machine']; ?></a></p>
		<h3>Asset Tag</h3>
		<p><?php echo $Ticket['AssetTag']; ?></p>
	</div>

</div>
<div class="breaker"></div>
<hr>
<div class="breaker"></div>
