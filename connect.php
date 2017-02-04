<?php
    include_once "config.php";
    include_once 'class.user.php';
    include_once "app.php";
    include_once "show_error.php";

    session_start();

    if (isset($_GET["appid"], $_GET["redirect_uri"], $_GET["response_type"], $_GET["scope"]) == false)
    {
        show_error(400, 'Missing arguments');
    }

    $appid = $_GET["appid"];
    $redirect_uri = $_GET["redirect_uri"];
    $response_type = $_GET["response_type"];
    $scope = $_GET["scope"];

    if ($response_type === "code")
    {
        $app = get_app_info_by_appid($appid, $dbh);
        if ($app)
        {
            
        }
        else {
            show_error(404, "Given app does not exist");
        }
    }
    else {
        show_error(400, "Incorrect response_type");
    }


    error_log($row, 0);

    $msg = "请登录以授权访问";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = new User($dbh);

        if($user->fetch_info_from_bbs($username, $password))
        {
            $_SESSION['username'] = $user->username;
            if ($user->is_new_user())
            {
                $_SESSION['email'] = $user->email;
                $_SESSION['bbsuid'] = $user->bbsuid;
                header("location: register.php");
            }
        } else {
            $msg = "用户名或密码不正确";
        }
    }
?>

<html>

<head>
    <title>登录 - 浙江大学广播电视台身份认证</title>
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="style/login.css" rel="stylesheet">
</head>

<body>
<div class = "container">

<h2>
    <img src="images/logo.png">
</h2>

<?php if (isset($_SESSION['username'])): ?>

<!-- Show authorization confirmation if user is logged in -->

<div class = "container">
    <h3>Logged in </h3>
</div>

<?php else: ?>

<!-- Show login form if user is not logged in -->

<div class = "container">
    <form class = "form-signin" role = "form" method = "post">
    <h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
        <input type = "text" class = "form-control" name = "username" placeholder = "用户名" required autofocus></br>
        <input type = "password" class = "form-control" name = "password" placeholder = "密码" required>
        <button class = "btn btn-lg btn-success btn-block" type = "submit" name = "login">登录</button>
    </form>
</div>

<?php endif ?>

</div>

</body>
</html>
