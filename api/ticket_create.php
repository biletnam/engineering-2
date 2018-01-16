<?php

require_once __DIR__.'/../_puff/sitewide.php';

$Runonce = Puff_Runonce_Exists($Sitewide['Database']['Connection'], $_POST['runonce']);
if ( !$Runonce ) {
	// Redirect to Last
	$SQL = 'SELECT MAX(`Ticket`) AS `MAX_Ticket` FROM `Tickets`;';
	$Last_ID = mysqli_fetch_once($Sitewide['Database']['Connection'], $SQL);
	$Last_ID = $Last_ID['MAX_Ticket'];
	$Location = 'Location: '.$Sitewide['Settings']['Site Root'].'ticket.php?id='.$Last_ID;
	header($Location, true, 302);
	exit;
} else {
	$Result = Puff_Runonce_Disable($Sitewide['Database']['Connection'], $_POST['runonce']);
}

if ( !empty($Sitewide['Authenticated']) ) {
	$Username = $Sitewide['Authenticated']['Member'];
	$Name     = $Sitewide['Authenticated']['Name'];
	$EMail    = $Sitewide['Authenticated']['EMail'];
	$Phone    = $Sitewide['Authenticated']['Phone'];
} else {
	$Username = false;
	$Name     = htmlentities($_POST['name'],  ENT_QUOTES, 'UTF-8');
	$EMail    = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
	$Phone    = htmlentities($_POST['phone'], ENT_QUOTES, 'UTF-8');
}

// Lookup Department, Line, and Machine by AssetTag
$Machine['Department'] = htmlentities($_POST['department'], ENT_QUOTES, 'UTF-8');
$Machine['Line']       = htmlentities($_POST['line'],       ENT_QUOTES, 'UTF-8');
$Machine['AssetTag']   = htmlentities($_POST['machine'],    ENT_QUOTES, 'UTF-8');
if (
	!empty($Machine['AssetTag']) &&
	$Machine['AssetTag'] != 'Air' &&
	$Machine['AssetTag'] != 'Building' &&
	$Machine['AssetTag'] != 'Drains' &&
	$Machine['AssetTag'] != 'Electrical' &&
	$Machine['AssetTag'] != 'Lighting' &&
	$Machine['AssetTag'] != 'Water' &&
	$Machine['AssetTag'] != 'Other'
) {
	$SQL = 'SELECT * FROM `Machines` WHERE `AssetTag`=\''.$Machine['AssetTag'].'\';';
	$Machine = mysqli_fetch_once($Sitewide['Database']['Connection'], $SQL);
} else {
	$Machine['Machine'] = $Machine['AssetTag'];
}

$Title       = htmlentities($_POST['title'],       ENT_QUOTES, 'UTF-8');
$Description = htmlentities($_POST['description'], ENT_QUOTES, 'UTF-8');

$SQL = 'INSERT INTO `Tickets`
(
	`Username`, `Name`, `EMail`, `Phone`,
	`Department`, `Line`, `Machine`, `AssetTag`,
	`Title`, `Description`,
	`CreatedTime`, `SignedOffTime`,
	`CreatedBy`, `SignedOffBy`,
	`Status`, `Assigned`, `SafetyChecks`
) VALUES (
	\''.$Username.'\', \''.$Name.'\', \''.$EMail.'\', \''.$Phone.'\',
	\''.$Machine['Department'].'\', \''.$Machine['Line'].'\', \''.$Machine['Machine'].'\', \''.$Machine['AssetTag'].'\',
	\''.$Title.'\', \''.$Description.'\',
	CURRENT_TIMESTAMP, \'0000-00-00 00:00:00\',
	\''.$Username.'\', \'\',
	\'New\', \'\', \'\'
);';
$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
$Ticket_ID = mysqli_insert_id($Sitewide['Database']['Connection']);

if ( $_FILES['upload']['size'] ) {
	$_FILES['upload']['name'] = preg_replace("/[^a-zA-Z0-9\.]+/", '', $_FILES['upload']['name']);
	$Result = move_uploaded_file($_FILES['upload']['tmp_name'], $Sitewide['Assets']['Internal']['Image'].'uploads/'.time().'_'.$_FILES['upload']['name']);
	$Comment = '[![]('.$Sitewide['Assets']['External']['Image'].'uploads/'.time().'_'.$_FILES['upload']['name'].')]('.$Sitewide['Assets']['External']['Image'].'uploads/'.time().'_'.$_FILES['upload']['name'].')';
	$Comment_ID = comment_on_ticket($Ticket_ID, $Username, $Comment);
}

if ( $Result ) {
	$Location = 'Location: '.$Sitewide['Settings']['Site Root'].'ticket.php?id='.$Ticket_ID;
	if ( isset($Comment_ID) ) {
		$Location .= '#comment-'.$Comment_ID;
	}
	header($Location, true, 302);
} else {
	echo 'Error #'.mysqli_errno($Sitewide['Database']['Connection']).' "'.mysqli_error($Sitewide['Database']['Connection']).'"';
}
