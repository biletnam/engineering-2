<?php

if ( isset($_GET['debug']) ) {
	$Debug = true;
} else {
	$Debug = false;
}
if ( $Debug ) $Start = microtime(true);

$Query = $_GET['query'];
if ( $Debug ) echo 'Searching for "'.$Query.'"'.PHP_EOL;

$FindParts = '
SELECT [STRC_CODE], [STRC_CODE2], [STRC_DESC]
FROM [Banham].[dbo].[Engineering_Products]
WHERE
[STRC_CODE] LIKE \'%'.$Query.'%\' OR
[STRC_CODE2] LIKE \'%'.$Query.'%\' OR
[STRC_DESC] LIKE \'%'.$Query.'%\';
';

// DEPENDS: Sets $DeFacto as a MSSQL Connection to the De Facto Database
include_once __DIR__.'/../_settings/defacto.custom.php';
$Parts = mssql_query($FindParts, $DeFacto);
if ( $Debug ) echo number_format(mssql_num_rows($Parts)).' parts found.'.PHP_EOL;
if ( $Debug ) $Finish = microtime(true);
if ( $Debug ) echo 'Took '.($Finish-$Start).' seconds.'.PHP_EOL;
while ( $Part = mssql_fetch_assoc($Parts) ) {
	$Part['STRC_CODE'] = utf8_encode($Part['STRC_CODE']);
	$Part['STRC_CODE2'] = utf8_encode($Part['STRC_CODE2']);
	$Part['STRC_DESC'] = utf8_encode($Part['STRC_DESC']);
	$JSON[] = $Part;
}
if ( $Debug ) var_dump($JSON);
$JSON = json_encode($JSON, JSON_PRETTY_PRINT);
if ( $Debug ) var_dump($JSON);
if ( !$JSON ) {
	echo 'JSON Error: '.json_last_error();
} else {
	echo $JSON;
}
