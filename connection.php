<?php
require_once(__DIR__ . "/../../config.php");
function getUserByEmail($email, $column = "*") {
    global $DB;
    $pictureId = $DB->get_record('user', ['email' => $email]);
    if($res === NULL) return -1;
    if($column === "*") return $res;
    return $res[$column];
}