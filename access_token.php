<?php

require_once "app.php";
require_once "redis.php";
require_once "db.php";

if (isset($_POST["appid"], $_POST["appsecret"], $_POST["code"], $_POST["grant_type"], $_POST["redirect_uri"]))
{
    $appid = $_POST["appid"];
    $appsecret = $_POST["appsecret"];
    $redirect_uri = urldecode($_POST["redirect_uri"]);

    if ($_POST["grant_type"] != "authorization_code")
    {
        echo '{"error": "invalid grant_type"}';
        exit();
    }

    $app_info = get_app_info_by_appid($appid, $dbh);
    if ($app_info && $app_info["secret"] === $_POST["appsecret"])
    {
        $token = exchange_access_token($appid, $redirect_uri, $_POST["code"]);
        if ($token)
        {
            $resp = ["access_token"=>$token, "expires_in"=>ACCESS_TOKEN_TIMEOUT,
            "user_id"=>get_userid_by_access_token($token)];
            echo json_encode($resp);
        }
        else {
            echo '{"error": "invalid code"}';
        }
    }
    else {
        echo '{"error": "invalid appid"}';
    }
}
else {
    echo '{"error": "bad request"}';
}

 ?>
