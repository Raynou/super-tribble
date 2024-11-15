<!DOCTYPE html>

<?php

if (!empty($_POST)) {
    require(__DIR__. '/../../../config.php');
    require(__DIR__ . "/../loginHandler.php");
    global $SESSION;
    
    $result = handleLogin($_POST['uri'], $_POST['email']);
    if ($credentials = json_decode($result, true)) {
        $SESSION->id = $credentials["id"];
        $SESSION->password = $credentials["password"];
    }
    die();
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"></script>
    <link rel="stylesheet" type="text/css" href="http://localhost/moodle/theme/styles.php/boost/1696118937_1/all" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>FaceID Login</title>
</head>
<body>
    <form method="POST" action="" id="form">
    <h1 class="h2 mb-3 text-cemter font-weight-normal">FaceID Login</h1>

    <input id="email" type="email" name="email" class="form-control" placeholder="correo@correo.com" required autofocus>
    
    <br>

    <video id="webcam" autoplay playsinline width="640" height="480"></video>
    <canvas id="canvas" class="d-none"></canvas>
    <audio id="snapSound" src="audio/snap.wav" preload = "auto"></audio>
    <img src="" alt="" id="userPicture">

    <br/>

    <div class="btn-group btn-block" role="group">
    <button id="takePhoto" type="button" class="btn btn-lg btn-primary">
    <i class="bi bi-camera"></i>
    Tomar foto
    </button>

    <button id="sendForm" type="submit" class="btn btn-lg btn-secondary">
    <i class="bi bi-send"></i>
    Enviar
    </button>
    </div>

    </form>
    <script src="./main.js"></script>
</body>
</html>
