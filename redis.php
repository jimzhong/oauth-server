<?php

define("ACCESS_CODE_TIMEOUT", 300);
define("ACCESS_TOKEN_TIMEOUT", 3600);

$redis = new Redis();

if ($redis->connect('127.0.0.1', 6379， 5.0) == false)
{
    throw new Exception('Connection to the redis server failed.');
}

function random_hex($bytes = 32)
{
    bin2hex(random_bytes($bytes));
}

// return access_code if successful
function new_access_code($appid, $userid, array $scopes)
{
    $access_code = random_hex(16);
    $handle = $redis->multi();
    $handle->hMSet("access_code:".$access_code, ["appid" => $appid, '$userid' => $userid])
    foreach ($scopes as $ascope)
    {
        $handle->sAdd("access_code:".$access_code.":scopes", $ascope);
    }
    $handle->expire("access_code:".$access_code, ACCESS_CODE_TIMEOUT);
    $handle->expire("access_code:".$access_code.":scopes", ACCESS_CODE_TIMEOUT);
    $ret = $handle->exec();
    return $access_code;
}

// return access_token if successful
// otherwise false
function exchange_access_token($appid, string $access_code)
{
    if ($redis->hGet("access_code:".$access_code, "appid") === $appid)
    {
        $access_token = random_hex(32);
        $handle = $redis->multi();
        $handle->rename("access_code:".$access_code, "access_token:".$access_token);
        $handle->rename("access_code:".$access_code.":scopes", "access_token:".$access_token.":scopes");
        $handle->expire("access_token:".$access_token.":scopes", ACCESS_TOKEN_TIMEOUT);
        $handle->expire("access_token:".$access_token, ACCESS_TOKEN_TIMEOUT);
        $handle->exec();
        return $access_token;
    }
    return false;
}

?>
