<?php

require_once "config.php";
require_once "db.php";
require_once "user.php";

$username = get_username_or_redirect();

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    if (change_password($_SESSION["userid"], $_POST["oldpw"], $_POST["newpw"], $dbh))
    {
        $msg = "修改成功";
    }
    else {
        $msg = "原密码不正确";
    }
}


?>

<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link href="/css/style.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>修改密码 - 浙江大学广播电视台身份认证</title>

    <style>

    </style>
  </head>

<body>

    <div class="container">
        <div class="header clearfix">
          <nav>
            <ul class="nav nav-pills pull-right">
                <li><a href="/home.php">个人主页</a></li>
                <li><a href="/apps.php">应用目录</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $username ?><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="/password.php">修改密码</a></li>
                      <li><a href="/profile.php">个人资料</a></li>
                      <li><a href="/logout.php">退出</a></li>
                    </ul>
                </li>
            </ul>
          </nav>
          <h3 class="text-muted">ZJUBTV</h3>
        </div>

    <div class="text-center" style="padding-bottom: 30px">
        <h3>修改密码</h3>
    </div>

    <div class="container">
        <form method="post" class="form-horizontal">
            <?php
            if (isset($msg)):
                echo '<div class="alert alert-success">';
                echo $msg;
                echo '</div>';
            endif;
            ?>
            <div class="form-group">
                <label class="col-sm-2 control-label">原密码</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="oldpw" name="oldpw" required/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">新密码</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="newpw" name="newpw" required/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">重复新密码</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="newpw-confirm" required/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button class="btn btn-primary" type="submit">提交</button>
                </div>
            </div>
        </form>
    </div>

    <?php include "footer.php"; ?>

</div>

</body>

<script>

var password = document.getElementById("newpw")
var confirm_password = document.getElementById("newpw-confirm");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("密码不匹配");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;

</script>

</html>
