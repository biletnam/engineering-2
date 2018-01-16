<h1 style="display:inline-block;"><i class="fa fa-wrench"></i> Engineering <span class="faded smaller">v2.0</span></h1>
<nav class="dont-print">
	<a class="navlink create-ticket" href="<?php echo $Sitewide['Settings']['Site Root']; ?>ticket_create.php"><i class="fa fa-plus"></i> Create Ticket</a>&emsp;
	<a class="navlink tickets" href="<?php echo $Sitewide['Settings']['Site Root']; ?>tickets.php"><i class="fa fa-ticket"></i> Tickets</a>&emsp;
	<?php

		if ( $Sitewide['Authenticated']['Department'] == 'Engineering' ) {
			echo '<a class="navlink engineering assigned" href="'.$Sitewide['Settings']['Site Root'].'tickets.php?assignee='.$Sitewide['Authenticated']['Username'].'"><i class="fa fa-users"></i> Assigned To</a>&emsp;';
		}

		if ( $Sitewide['Authenticated']['Role'] == 'Admin' ) {
			echo '<a class="navlink admin member-create" href="'.$Sitewide['Settings']['Site Root'].'admin/member_create.php"><i class="fa fa-user-plus"></i> Create a User</a>&emsp;';
			echo '<a class="navlink admin members" href="'.$Sitewide['Settings']['Site Root'].'admin/members.php"><i class="fa fa-users"></i> Manage Users</a>&emsp;';
		}

		if ( !empty($Sitewide['Authenticated']) ) {
			echo '<a class="navlink authenticate log-out" href="'.$Sitewide['Settings']['Site Root'].'api/log_out.php?redirect='.urlencode($Sitewide['Request']['Full']).'"><i class="fa fa-lock"></i> Log Out</a>&emsp;';
		} else {
			echo '<a class="navlink authenticate log-in" href="'.$Sitewide['Settings']['Site Root'].'log_in.php?redirect='.urlencode($Sitewide['Request']['Full']).'"><i class="fa fa-unlock-alt"></i> Log In</a>&emsp;';
		}
	?>
</nav>
<div class="clear"></div>
<hr>
<br>
