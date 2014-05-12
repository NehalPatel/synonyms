<?php
if(!isset($_REQUEST['query'])){
    header('Content-Type: application/json');
    echo json_encode(array());
    exit;
}

ini_set('max_execution_time', 6000);    //10min
include_once 'bighugelabs_class.php';

$synonyms = new BigHugeLabs();
$keywords = $_REQUEST['query'];
$result = $synonyms->generateCSV($keywords);

echo json_encode($result);
exit;
?>