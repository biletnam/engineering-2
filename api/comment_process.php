<?php

require_once __DIR__.'/../_puff/sitewide.php';

$Comment['Ticket'] = htmlentities($_POST['ticket'], ENT_QUOTES, 'UTF-8');
$Comment['Comment'] = htmlentities($_POST['comment'], ENT_QUOTES, 'UTF-8');

$Runonce = Puff_Runonce_Exists($Sitewide['Database']['Connection'], $_POST['runonce']);
$SQL = 'SELECT MAX(`ID`) AS `MAX_ID` FROM `Comments` WHERE `Ticket_ID` = \''.$Comment['Ticket'].'\';';
$Last_ID = mysqli_fetch_once($Sitewide['Database']['Connection'], $SQL);
$Comment_ID = $Last_ID['MAX_ID'];
if ( !$Runonce ) {
	// Redirect to Last
	$Location = 'Location: '.$Sitewide['Settings']['Site Root'].'ticket.php?id='.$Comment['Ticket'].'#comment-'.$Comment_ID;
	header($Location, true, 302);
	exit;
} else {
	$Result = Puff_Runonce_Disable($Sitewide['Database']['Connection'], $_POST['runonce']);
}

if ( !empty($Sitewide['Authenticated']) ) {
	$Comment['Username'] = $Sitewide['Authenticated']['Member'];
} else {
	// TODO Error
}

if ( !empty($Comment['Comment']) ) {
	$Comment_ID = comment_on_ticket($Comment['Ticket'], $Comment['Username'], $Comment['Comment']);
}

if ( $_FILES['upload']['size'] ) {
	$_FILES['upload']['name'] = preg_replace("/[^a-zA-Z0-9\.]+/", '', $_FILES['upload']['name']);
	$FileInfo = getimagesize($_FILES['upload']['tmp_name']);
	$Result = move_uploaded_file($_FILES['upload']['tmp_name'], $Sitewide['Assets']['Internal']['Image'].'uploads/'.time().'_'.$_FILES['upload']['name']);
	if ( $FileInfo ) {
		$Comment['Actual'] = '[![]('.$Sitewide['Assets']['External']['Image'].'uploads/'.time().'_'.$_FILES['upload']['name'].')]('.$Sitewide['Assets']['External']['Image'].'uploads/'.time().'_'.$_FILES['upload']['name'].')';
	} else {
		$Comment['Actual'] = '[Download "'.$_FILES['upload']['name'].'" ]('.$Sitewide['Assets']['External']['Image'].'uploads/'.time().'_'.$_FILES['upload']['name'].')';
	}
	$Comment_ID = comment_on_ticket($Comment['Ticket'], $Comment['Username'], $Comment['Actual']);
}

header('Location: '.$Sitewide['Settings']['Site Root'].'ticket.php?id='.$Comment['Ticket'].'#comment-'.$Comment_ID, true, 302);
