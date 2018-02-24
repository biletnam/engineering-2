<?php

require_once __DIR__.'/../_puff/sitewide.php';

$Ticket_ID = htmlentities($_GET['ticket'], ENT_QUOTES, 'UTF-8');
$Description = htmlentities($_GET['description'], ENT_QUOTES, 'UTF-8');
$Quantity = htmlentities($_GET['quantity'], ENT_QUOTES, 'UTF-8');

$SQL = 'INSERT INTO `Parts` (`Ticket ID`, `STRC_CODE`, `STRC_CODE2`, `STRC_DESC`, `Quantity`) VALUES (\''.$Ticket_ID.'\', \'MANUAL_ENTRY\', \'MANUAL_ENTRY\', \''.$Description.'\', \''.$Quantity.'\');';
$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
//var_dump($SQL);
//var_dump($Result);
echo json_encode(array('result' => $Result));
