<?php

require_once __DIR__.'/../_puff/sitewide.php';

$Ticket_ID = htmlentities($_GET['ticket'], ENT_QUOTES, 'UTF-8');
$STRC_CODE = htmlentities($_GET['STRC_CODE'], ENT_QUOTES, 'UTF-8');
$STRC_CODE2 = htmlentities($_GET['STRC_CODE2'], ENT_QUOTES, 'UTF-8');
$STRC_DESC = htmlentities($_GET['STRC_DESC'], ENT_QUOTES, 'UTF-8');
$Quantity = htmlentities($_GET['quantity'], ENT_QUOTES, 'UTF-8');

$SQL = 'INSERT INTO `Parts` (`Ticket ID`, `STRC_CODE`, `STRC_CODE2`, `STRC_DESC`, `Quantity`) VALUES (\''.$Ticket_ID.'\', \''.$STRC_CODE.'\', \''.$STRC_CODE2.'\', \''.$STRC_DESC.'\', \''.$Quantity.'\');';
$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
//var_dump($SQL);
//var_dump($Result);
echo json_encode(array('result' => $Result));
