<?php
    require_once "db.php";
    require_once "user.php";

    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        $user_info = get_user_info_by_username($username, $dbh);

        // var_dump($user_info);

        if($user_info && password_verify($password, $user_info["password"]))
        {
            user_login($user_info["userid"], $user_info["username"]);

            if (isset($_GET["next"]))
            {
                // TODO: check validity of next location
                header("location: ".urldecode($_GET["next"]));
            } else {
                header("location: home.php");
            }
        } else {
            $msg = "用户名或密码不正确";
        }
    }
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>登录 - 浙江大学广播电视台身份认证</title>
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
            <div class="panel-heading" style="padding: 16px">
                <h1 class="panel-title" style="font-size: 2rem">请登录</h1>
            </div>

            <div class="panel-body">
                <?php
                if (isset($msg)):
                    echo '<div class="alert alert-warning">';
                    echo $msg;
                    echo '</div>';
                endif;
                ?>
                <form class = "form-horizontal" role = "form" method = "post">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input type="text" class="form-control" name="username" placeholder="用户名" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input type="password" class="form-control" name="password" placeholder="密码" required>
                        <div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <button class="btn btn-primary" type="submit" name="login">登录</button>
                            <a class="btn btn-default" href="/register.php">注册</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<?php include "footer.php" ?>

</div>


</body>
</html>
