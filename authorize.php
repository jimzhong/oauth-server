<?php

require_once "db.php";
require_once "show_error.php";
require_once "app.php";
require_once "redis.php";

session_start();

if (isset($_SESSION['userid']) == false)
{
    show_error(401, "Unauthorized");
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_POST["appid"], $_POST["redirect_uri"], $_POST["response_type"], $_POST["scope"]) == false)
    {
        show_error(400, 'Missing arguments');
    }

    $appid = htmlspecialchars($_POST["appid"]);
    $redirect_uri = htmlspecialchars(urldecode($_POST["redirect_uri"]));
    $response_type = htmlspecialchars($_POST["response_type"]);
    $scope = htmlspecialchars(urldecode($_POST["scope"]));
    $state = htmlspecialchars($_POST["state"]);

    if (is_valid_redirect_uri($appid, $redirect_uri, $dbh))
    {
        $scopes = explode(" ", $scope);
        $code = new_access_code($appid, $_SESSION["userid"], $redirect_uri, $scopes);
        $redirect_uri = "$redirect_uri?code=$code";
        if (!empty($state))
        {
            $redirect_uri = "$redirect_uri&state=$state";
        }
        header("location: $redirect_uri");
    }
    else {
        show_error(400, "(Redirect URI, AppID) is not valid");
    }

}
else {
    show_error(405, "Method not allowed");
}

 ?>
