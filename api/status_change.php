<?php

require_once __DIR__.'/../_puff/sitewide.php';
$Comment['Username'] = Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Sitewide['Authenticated']['Member'], 'Name');
$Comment['Status'] = htmlentities($_POST['status'], ENT_QUOTES, 'UTF-8');
$Comment['Ticket'] = htmlentities($_POST['ticket'], ENT_QUOTES, 'UTF-8');

if (
	(
		(
			$Sitewide['Authenticated']['Department'] == 'Engineering' &&
			$Sitewide['Authenticated']['Role'] == 'Manager'
		) ||
		$Sitewide['Authenticated']['Role'] == 'Admin'
	) &&
	$Comment['Status'] != 'no-change'
) {

	$SQL = 'SELECT * FROM `Tickets` WHERE `Ticket`=\''.$Comment['Ticket'].'\';';
	$Ticket = mysqli_fetch_once($Sitewide['Database']['Connection'], $SQL);

	$SQL = 'UPDATE `Tickets` SET `Status`=\''.$Comment['Status'].'\' WHERE `Ticket`=\''.$Comment['Ticket'].'\';';
	$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
	if ( !$Result ) {
		echo 'Error #'.mysqli_errno($Sitewide['Database']['Connection']).' "'.mysqli_error($Sitewide['Database']['Connection']).'"';
	}

	$SQL = 'SELECT * FROM `Statuses` WHERE `Status` = \''.$Comment['Status'].'\';';
	$Status = mysqli_fetch_once($Sitewide['Database']['Connection'], $SQL);
	// Insert Comment
	// TODO Previous
	$Comment['Actual'] = $Comment['Username'].' changed the ticket status to <span class="status-tag color-white background-'.$Status['Color'].'">'.$Comment['Status'].'</span>';
	if ( $Comment['Status'] =='Signed Off' ) {
		$Comment['Actual'] .= PHP_EOL.PHP_EOL;
		$Comment['Actual'] .= '<input type="checkbox" name="debris" required checked disabled> Has all debris including redundant parts been cleared away and disposed of?  '.PHP_EOL;
		$Comment['Actual'] .= '<input type="checkbox" name="tools" required checked disabled> Have all tools been accounted for?  '.PHP_EOL;
		$Comment['Actual'] .= '<input type="checkbox" name="parts" required checked disabled> Have any parts found missing been reported?  '.PHP_EOL;
		$Comment['Actual'] .= '<input type="checkbox" name="equipment" required checked disabled> Has all equipment used for access, such as ladders etc. been cleared and stored?  '.PHP_EOL;
		$SQL = 'UPDATE `Tickets` SET `SignedOffTime`=CURRENT_TIMESTAMP, `SignedOffBy`=\''.$Sitewide['Authenticated']['Member'].'\' WHERE `Ticket`=\''.$Comment['Ticket'].'\';';
		$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
	}
	$Comment_ID = comment_on_ticket($Comment['Ticket'], 'SYSTEM', $Comment['Actual']);
}
header('Location: '.$Sitewide['Settings']['Site Root'].'ticket.php?id='.$Comment['Ticket'].'#comment-'.$Comment_ID, true, 302);
