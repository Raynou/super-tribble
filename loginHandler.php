<?php

require_once(__DIR__ . "/vendor/autoload.php");
require_once(__DIR__ . '/../../config.php');
require_once("connection.php");

use Stormwind\FaceAnalyzer;
use Stormwind\ImageHandler;
use Dotenv\Dotenv;

function handleLogin($uri, $email) {

    $tmpFolderPath = __DIR__ . "/tmp";
    $logsFilePath = __DIR__ . "/logs";

    $logs = fopen($logsFilePath, "w");

    // Creates a folder to save the images that will be compared
    if(!file_exists($tmpFolderPath)) {
        mkdir($tmpFolderPath);
    }

    $userPicture = getUserByEmail($email, "picture");

    /*The value zero for the user picture in the moodle database is reserved
     for the guest user.

     For more information about the guest user, please see: https://docs.moodle.org/403/en/Guest_role
     */
    if($userPicture === 0) 
    {
        throw new Exception("User picture value can't be zero");
    }

    // Gets user's profile image from the Moodle Fyle System
    $profileImagePath = $tmpFolderPath . "/profile.png";
    ImageHandler::getImageFromURL($userPicture, $profileImagePath);

    // Converts the uri recived in the HTTP request into an image
    $photoTargetPath = $tmpFolderPath . "/login.png";
    ImageHandler::base64ToImage($uri, $photoTargetPath);

    if($credentials != null) {
        FaceAnalyzer::setCredentials($credentials);
    }

    if(FaceAnalyzer::compareFaces($profileImagePath, $photoTargetPath)) {
        $id = getUserByEmail($email, "id");
        $password = getUserByEmail($email, "password");
        return array("id" => $id, "password" => $password);
    }else {
        return "Error: Faces don't match";
    }
    
}