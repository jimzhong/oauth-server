<?php

session_start();

if (isset($_SESSION["username"]))
{
    $username = $_SESSION["username"];
}
else {
    header("location: login.php");
    exit();
}

?>

<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>主页 - 浙江大学广播电视台身份认证</title>

    <style>
    .navbar-brand img
    {
        max-height: 100%;
    }
    .navbar-brand
    {
        margin: 0;
        padding: 0;
    }
    </style>
  </head>

<body>
  <nav class="navbar navbar-fixed-top navbar-inverse">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand"><img alt="Brand" src="/images/brand.png"></a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a href="/home.php">个人主页</a></li>
          <li><a href="/allapps.php">应用目录</a></li>
          <li><a href="/contact.php">通讯录</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $username ?><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="/password.php">修改密码</a></li>
                    <li><a href="/profile.php">个人资料</a></li>
                    <!-- <li><a href="#">Something else here</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#">Separated link</a></li> -->
                </ul>
            </li>
        </ul>
      </div><!-- /.nav-collapse -->
    </div><!-- /.container -->
  </nav><!-- /.navbar -->

    <div class="jumbotron">
      <div class="container">
        <h1>Hello, world!</h1>
        <p>Under construction</p>
      </div>
    </div>


</body>

</html>
