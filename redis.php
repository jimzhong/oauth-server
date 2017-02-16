<?php

define("ACCESS_CODE_TIMEOUT", 300);
define("ACCESS_TOKEN_TIMEOUT", 3600);

$redis = new Redis();

if ($redis->connect('127.0.0.1', 6379, 5.0) === false)
{
    throw new Exception('Connection to the redis server failed.');
}

function random_hex($bytes = 32)
{
    return bin2hex(random_bytes($bytes));
}

// return access_code if successful
function new_access_code($appid, $userid, $redirect_uri, array $scopes)
{
    global $redis;
    $access_code = random_hex(20);
    $handle = $redis->multi();
    $handle->hMSet("access_code:".$access_code,
        ["appid" => $appid, 'userid' => $userid, 'redirect_uri' => $redirect_uri]);
    foreach ($scopes as $ascope)
    {
        $handle->sAdd("access_code:".$access_code.":scopes", $ascope);
    }
    $handle->expire("access_code:".$access_code, ACCESS_CODE_TIMEOUT);
    $handle->expire("access_code:".$access_code.":scopes", ACCESS_CODE_TIMEOUT + 10);
    $ret = $handle->exec();
    return $access_code;
}

// return access_token if successful
// otherwise false
function exchange_access_token($appid, $redirect_uri, string $access_code)
{
    global $redis;
    if (($redis->hGet("access_code:".$access_code, "appid") == $appid)
        && ($redis->hGet("access_code:".$access_code, "redirect_uri") == $redirect_uri))
    {
        $access_token = random_hex(32);
        $handle = $redis->multi();
        $handle->rename("access_code:".$access_code, "access_token:".$access_token);
        $handle->rename("access_code:".$access_code.":scopes", "access_token:".$access_token.":scopes");
        $handle->expire("access_token:".$access_token.":scopes", ACCESS_TOKEN_TIMEOUT + 10);
        $handle->expire("access_token:".$access_token, ACCESS_TOKEN_TIMEOUT);
        $handle->exec();
        return $access_token;
    }
    return false;
}

function get_userid_by_access_token($access_token)
{
    global $redis;
    return $redis->hGet("access_token:".$access_token, "userid");
}

function get_scopes_by_access_token($access_token)
{
    global $redis;
    return $redis->sMembers("access_token:".$access_token.":scopes");
}

// if (!debug_backtrace()) {
//     $code = new_access_code(123, 111, "aaa", ["email", "phone"]);
//     echo $code;
//     $token = exchange_access_token(123, "aaa", $code);
//     echo $token;
// }

?>
