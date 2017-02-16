<?php

// if a app with appid exists, return its info in an assoc Array
// otherwise return false
function get_app_info_by_appid($appid, $dbh)
{
    $sth = $dbh->prepare("SELECT * FROM apps WHERE appid=:appid LIMIT 1");
    $sth->execute(array(':appid' => $appid));
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    if ($row)
    {
        return $row;
    } else {
        return false;
    }
}

function is_valid_redirect_uri($appid, $uri, $dbh)
{
    $sth = $dbh->prepare("SELECT redirect_uri FROM redirect_uris WHERE appid=:appid AND redirect_uri=:uri");
    $sth->execute([":appid"=>$appid, ":uri"=>$uri]);
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    if ($row)
        return true;
    else {
        return false;
    }
}

function get_scope_description(string $scope, $dbh)
{
    $sth = $dbh->prepare("SELECT description FROM scopes WHERE name=:scope LIMIT 1");
    $sth->execute([":scope" => $scope]);
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    if ($row)
        return $row['description'];
    else {
        return false;
    }
}

// // if an authorization containing (appid, userid, scope) exists return its refresh_token
// // otherwise return false
// function get_refresh_token($appid, $userid, $scope, $dbh)
// {
//     $sth = $dbh->prepare("SELECT refresh_token FROM authorization
//         WHERE app_id=:appid AND user_id=:userid AND scope=:scope LIMIT 1");
//     $sth->execute(array(':appid' => $appid, "userid" => $userid, "scope" => $scope));
//     $row = $sth->fetch(PDO::FETCH_ASSOC);
//     if ($row)
//         return $row["refresh_token"];
//     else
//         return false;
// }
//
// function new_authorization_entry($appid, $userid, $scope, $dbh)
// {
//     $refresh_token = bin2hex(random_bytes(32));
//     $sth = $dbh->prepare("INSERT INTO authorization (app_id, user_id, scope, refresh_token)
//         VALUES (:appid, :userid, :scope, :refresh_token)");
//     $sth->execute(array(':appid' => $appid, "userid" => $userid, "scope" => $scope, "refresh_token" => $refresh_token));
// }

?>
