<?php

function get_user_info_by_username($username, $dbh)
{
    $sth = $dbh->prepare("SELECT * FROM users WHERE username=:username LIMIT 1");
    $sth->execute([":username" => $username]);
    $row = $sth->fetch(PDO::FETCH_ASSOC);

    return $row;
}

function user_login($userid, $username)
{
    session_start();
    $_SESSION["userid"] = $userid;
    $_SESSION["username"] = $username;
}

function is_valid_staff_zjuid($zjuid, $dbh)
{
    $sth = $dbh->prepare("SELECT * FROM staff WHERE zjuid=:zjuid LIMIT 1");
    $sth->execute([":zjuid"=>$zjuid]);
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    if ($row)
        return true;
    return false;
}

// add a new user, return true on success
// will raise exceptions on failures
function add_user($username, $password, $zjuid, $phone_long, $phone_short, $email, $dbh)
{
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $data = [$username, $email, $hashed_password, $zjuid, $phone_long, $phone_short];
    $sth = $dbh->prepare("INSERT INTO users(username,email,password,zjuid,phone_long,phone_short) VALUES(?, ?, ?, ?, ?, ?)");
    return $sth->execute($data);
}

 ?>
