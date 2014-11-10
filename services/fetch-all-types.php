﻿<?php
/* __________ CONFIGURATION ____________ */
if (!defined("INCLUDES_PATH")){
	require_once("../config.php");
}
/* ¯¯¯¯¯¯¯¯¯¯ CONFIGURATION ¯¯¯¯¯¯¯¯¯¯¯¯ */

require_once(INCLUDES_PATH . '/dbi_functions_sqlite.php');
//require_once(INCLUDES_PATH . '/debug_functions.php');
//TurnDebugOff();

$conn = new SuperConnection();

if($conn) {
    //$query = "select * from ingredients";
    //$query = "select * from types";

    $query = "select types.id id,
                types.name name
              from types";

    $results = $conn->ExecuteArrayQuery($query);
    $json = json_encode($results);
    echo '<pre>', print_r($results, true), '</pre>';
}
//echo '<pre>', json_encode($results, JSON_PRETTY_PRINT), '</pre>';
//echo '<pre>', json_encode($prettyJson), '</pre>';
//trace(print_r($result, true));
?>