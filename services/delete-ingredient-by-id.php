<?php
/* __________ CONFIGURATION ____________ */
if (!defined("INCLUDES_PATH")){
    require_once("../config.php");
}
/* ¯¯¯¯¯¯¯¯¯¯ CONFIGURATION ¯¯¯¯¯¯¯¯¯¯¯¯ */

require_once(INCLUDES_PATH . '/dbi_functions_sqlite3.php');
//require_once(INCLUDES_PATH . '/debug_functions.php');
//TurnDebugOff();

$db = new Sqlite3Connection();

if($db) {
    $postdata = file_get_contents("php://input");
    $postdata = json_decode($postdata, true);

    if(isset($postdata['id']) and !empty($postdata['id'])) {
        $ingredientID = $postdata['id'];

        $query = "delete from ingredients
                  where id = $ingredientID";

        $query2 = "delete from ingredients_types
                    where ingredients_id = $ingredientID";

        $results = $db->ExecuteQuery($query);
        $results2 = $db->ExecuteQuery($query2);
        echo "true";
    }
} else {
    echo "false";
}
//echo '<pre>', json_encode($results, JSON_PRETTY_PRINT), '</pre>';
//echo '<pre>', json_encode($prettyJson), '</pre>';
//trace(print_r($result, true));
?>