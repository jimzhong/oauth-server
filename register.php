<?php

require_once "db.php";
require_once "user.php";
require_once "show_error.php";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    session_start();

    $username = htmlspecialchars(trim($_POST["username"]));
    $password = $_POST["password"];
    $zjuid = htmlspecialchars(trim($_POST["zjuid"]));
    $phone_long = htmlspecialchars(trim($_POST["phone-long"]));
    $phone_short = htmlspecialchars(trim($_POST["phone-short"]));
    $email = htmlspecialchars(trim($_POST["email"]));

    if ($_SESSION["captcha"] != trim($_POST["captcha"]))
    {
        $err = "验证码不正确";
    }
    else if (is_valid_staff_zjuid($zjuid, $dbh) == false)
    {
        $err = "该学工号不允许注册";
    }
    else if ($username && $password && $zjuid && $phone_long && $email)
    {
        try
        {
            add_user($username, $password, $zjuid, $phone_long, $phone_short, $email, $dbh);
            $msg = "注册成功";
            header("location: login.php");
        }
        catch (Exception $e)
        {
            $err = $e->getMessage();
            if (strstr($err, 'username'))
                $err = "用户名已被占用";
            else if (strstr($err, "email"))
                $err = "电子邮箱地址已被占用";
            else if (strstr($err, "zjuid"))
                $err = "学工号已被占用";
        }
    } else {
        $err = "请填写完整后提交";
    }
} else {
    $username = $zjuid = $phone_long = $phone_short = $email = "";
    $msg = "当前仅允许浙江大学广播电视台成员注册";
}

?>

<html>
  <head>
     <title>注册 - 浙江大学广播电视台身份认证</title>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">

     <link href="/css/style.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
     <script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
     <script src="https://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

  </head>

<body>

    <div class="container">
        <div class="page-header">
          <h1>新用户注册</h1>
        </div>
        <div class="row">
          <form method="post">
              <div class="col-xs-12">
              <?php
              if (isset($msg)):
                  echo '<div class="alert alert-success">';
                  echo $msg;
                  echo '</div>';
              endif;
              if (isset($err)):
                  echo '<div class="alert alert-warning">';
                  echo $err;
                  echo '</div>';
              endif;
              ?>
            </div>
              <div class="form-group col-sm-6 col-sx-12">
                <label for="username">用户名</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username ?>" required>
              </div>
            <div class="form-group col-sm-6 col-sx-12">
              <label for="zjuid">学工号</label>
              <input type="number" class="form-control" id="zjuid" name="zjuid" value="<?php echo $zjuid ?>" required>
            </div>
            <div class="form-group col-sm-6 col-sx-12">
              <label for="realname">密码</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group col-sm-6 col-sx-12">
              <label for="realname">密码确认</label>
              <input type="password" class="form-control" id="password-confirm" name="password-confirm" required>
            </div>
            <div class="form-group col-sm-6 col-sx-12">
              <label for="phone-long">联系电话</label>
              <input type="tel" class="form-control" id="phone-long" name="phone-long" value="<?php echo $phone_long ?>" required>
            </div>
            <div class="form-group col-sm-6 col-sx-12">
              <label for="phone-short">手机短号（可选）</label>
              <input type="tel" class="form-control" id="phone_short" name="phone-short" value="<?php echo $phone_short ?>" >
            </div>
            <div class="form-group col-sm-6 col-sx-12">
              <label for="email">电子邮箱</label>
              <input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" required>
            </div>
            <div class="form-group col-sm-6 col-sx-12">
              <label for="captcha">验证码</label>
              <img src="/captcha.php" alt="CAPTCHA">
              <input type="text" class="form-control" id="captcha" name="captcha" required>
            </div>

            <div class="form-group">
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary btn-block">提交</button>
              </div>
            </div>
          </form>

      </div>

    <?php include "footer.php" ?>
    </div>

</body>

<script>

var password = document.getElementById("password")
var confirm_password = document.getElementById("password-confirm");

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
