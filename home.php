<?php

require_once "config.php";
require_once "db.php";
require_once "user.php";

$username = get_username_or_redirect();

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

    <title>主页 - 浙江大学广播电视台身份认证</title>

    <style>

    </style>
  </head>

<body>

    <div class="container">
        <div class="header clearfix">
          <nav>
            <ul class="nav nav-pills pull-right">
                <li class="active"><a href="/home.php">个人主页</a></li>
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


    <div class="jumbotron">
      <div class="container">
        <h1>Hello, world!</h1>
        <p>Under construction</p>
      </div>
    </div>

    <?php include "footer.php"; ?>

</body>

</html>
