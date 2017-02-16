<?php
    include_once "db.php";
    // include_once 'class.user.php';
    include_once "app.php";
    include_once "show_error.php";

    session_start();

    if (isset($_GET["appid"], $_GET["redirect_uri"], $_GET["response_type"], $_GET["scope"]) == false)
    {
        show_error(400, 'Missing arguments');
    }

    $appid = htmlspecialchars($_GET["appid"]);
    $redirect_uri = htmlspecialchars(urldecode($_GET["redirect_uri"]));
    $response_type = htmlspecialchars($_GET["response_type"]);
    $scope = htmlspecialchars(urldecode($_GET["scope"]));

    $appinfo = get_app_info_by_appid($appid, $dbh);
    if ($appinfo == false)
    {
        show_error(400, "AppID is invalid");
    }
    //check redirect_uri against DB
    if (is_valid_redirect_uri($appid, $redirect_uri, $dbh) == false)
    {
        show_error(400, "Redirect URI is invalid");
    }

    if (isset($_SESSION['username']))
    {
        // user is logged in, translate scopes to descriptions
        $scopes = explode(" ", $scope);
        $scope_descriptions = [];
        foreach ($scopes as $ascope) {
            $desc = get_scope_description($ascope);
            // only display valid scopes
            if ($desc)
                $scope_descriptions[] = $desc;
        }
    }
    else {
        // jump to login page
        header("location: login.php?next=".urlencode("/connect.php?".$_SERVER['QUERY_STRING']));
    }
?>

<html>


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <!-- <script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script> -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>授权 - 浙江大学广播电视台身份认证</title>
</head>

<body>

<div class = "container">

    <nav class="navbar navbar-default" role="navigation" style="background: white; display: flex; padding: 16px; margin-top: 1.5rem;">
        <div class="container-fluid">
            <div class="navbar-header clearheader">
                <a class="navbar-brand" href="#" style="padding: 0 1rem 0 1rem ;"><img src="images/logo.png" style="height:5rem"></a>
            </div>
        </div>
    </nav>

<div class="container">
    <div class="col-sm-offset-2 col-sm-8 col-xs-12 col-md-offset-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-body">
                <h4><?php echo $appinfo["name"]?>需要获取这些权限:</h4>
                <ul>
                    <?php foreach ($scope_descriptions as $desc) {
                        echo "<li>$desc</li>";
                    }
                    echo "\n";
                    ?>
                </ul>
                <div class="container">
                    <form action="/authorize.php" method="post">
                        <input type="hidden" value="<?php echo $appid ?>" name="appid" />
                        <input type="hidden" value="<?php echo urlencode($scope) ?>" name="scope" />
                        <input type="hidden" value="<?php echo urlencode($redirect_uri) ?>" name="redirect_uri" />
                        <input type="hidden" value="<?php echo $response_type ?>" name="response_type" />
                        <button type="submit" class="btn btn-primary">允许</button>
                        <button class="btn btn-default">拒绝</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<?php include "footer.php" ?>

</body>
</html>
