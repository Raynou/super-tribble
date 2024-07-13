<?php

require_once("./vendor/autoload.php");
require_once(__DIR__ . '/../../../config.php');

header("Content-Type:application/json");

use Stormwind\FaceAnalyzer;
use Stormwind\QueryHandler;
use Stormwind\ImageHandler;
use Dotenv\Dotenv;

$method = $_SERVER["REQUEST_METHOD"];

switch($method) {
    case "POST":
        $requestBody = file_get_contents('php://input');

        list($email, $uri) = explode("&", $requestBody);
        $email = urldecode(explode("=", $email)[1]);
        $uri = urldecode(explode("=", $uri)[1]);
        $str = handleLogin($uri, $email);
        echo(json_encode($str));
        break;
}

function handleLogin($uri, $email) {

    $logs = fopen("./logs", "w");

    // Creates a folder to save the images that will be compared
    if(!file_exists(getcwd()."/tmp")) {
        mkdir("tmp");
    }

    // Load enviorment vars if exists a configuration file
    $dotenvPath = __DIR__ . '/.env';
    if(file_exists($dotenvPath)) {
        fwrite($logs, "Existe archivo .env\n");
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $queryHandler = new QueryHandler();
    } else if(get_config("auth_faceid") != null) { // Get credentials from Moodle config
        $credentials = (array) get_config('auth_faceid');
        fwrite($logs, json_encode($credentials));
        $queryHandler = new QueryHandler($credentials);
    }

    $userPicture = $queryHandler->getUserPicture($email);

    /*The value zero for the user picture in the moodle database is reserved
     for the guest user.

     For more information about the guest user, please see: https://docs.moodle.org/403/en/Guest_role
     */
    if($userPicture === 0) 
    {
        throw new Exception("User picture value can't be zero");
    }

    // Gets user's profile image from the Moodle Fyle System
    $profileImagePath = "./tmp/profile.png";
    ImageHandler::getImageFromURL($userPicture, $profileImagePath);

    // Converts the uri recived in the HTTP request into an image
    $photoTargetPath = "./tmp/login.png";
    ImageHandler::base64ToImage($uri, $photoTargetPath);

    if($credentials != null) {
        FaceAnalyzer::setCredentials($credentials);
    }

    if(FaceAnalyzer::compareFaces($profileImagePath, $photoTargetPath)) {
        $id = $queryHandler->getUserId($email);
        $password = $queryHandler->getUserPassword($email);
        return array("id" => $id, "password" => $password);
    }else {
        return "Error: Faces don't match";
    }
    
}