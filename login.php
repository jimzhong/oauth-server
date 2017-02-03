<?php
    include("config.php");
    include_once 'class.user.php';

    session_start();

    $msg = "";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = new User($dbh);

        if($user->fetch_info_from_bbs($username, $password))
        {
            $_SESSION['username'] = $user->username;
            $_SESSION['email'] = $user->email;
            $_SESSION['bbsuid'] = $user->bbsuid;
            if ($user->is_new_user_from_bbs())
            {
                header("location: register.php");
            } else {
                header("location: dashboard.php");
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

    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <!-- <script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script> -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <!-- <link href="https://cdn.bootcss.com/font-awesome/4.6.2/css/font-awesome.min.css" rel="stylesheet"> -->
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
                if (!empty($msg)):
                    echo '<div class="alert alert-warning">';
                    echo $msg;
                    echo '</div>';
                endif
                ?>
                <form class = "form-horizontal" role = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
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
                            <button class = "btn btn-primary btn-block" type = "submit" name = "login">登录</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
