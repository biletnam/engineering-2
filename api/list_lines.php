<?php

require_once __DIR__.'/../_puff/sitewide.php';

$Department = htmlentities($_GET['department'], ENT_QUOTES, 'UTF-8');
$SQL = 'SELECT * FROM `Lines` WHERE `Active`=\'1\' AND `Department`=\''.$Department.'\' ORDER BY `Order` DESC, `Department` ASC, `Line` ASC;';
$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
while ( $Line = mysqli_fetch_assoc($Result) ) {
	$Data[] = $Line['Line'];
}
$Data[] = 'Other';

echo json_encode($Data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);