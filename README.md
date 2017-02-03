OAuth 2.0 Server
======================

## Background

OAuth 2.0 is a protocol for authorization. There is a centeral authorization server and various web apps. Each app has a unique AppID and an AppSecret. When a user want to use one web app. The user will be redirected to a page at the authorization server. If the user is authenticated. The user will be prompted to give permission to the app. If permission is granted, the authorization server will redirect the user to a URL on the web app with a special code. The web app can then get the user's information by sending requests to the authorization server with its AppID, AppSecret and the code.

## Flow

### 1. Get the authorization code

redirect the user to the authorization endpoint `https://auth.zjubtv.com/connect.php?appid=APPID&response_type=code&scope=SCOPE&state=STATE`

Name | Required | Description
-----|---------|------
appid| Yes  | AppID
redirect_uri | No | not supported in this implementation
response_type | Yes | use `code` for now, may support `token` in the future
scope | Yes | space delimited authorization scopes, currently only `baiscuserinfo`
state | No | for CSRF protection, will be copied to redirect_uri

If the user gives permission to this app, the authorization endpoint will redirect the user to `REDIRECT_URI?code=CODE&state=STATE`. The `REDIRECT_URI` is the redirect URI registered for that app in the database.

If anything goes wrong, the user agent will be redirected to `REDIRECT_URI?error=ERRORMSG`. The possible values of `error` includes:

Value | Description
-----| -------------
invalid_appid | AppID is invalid, no such app
access_denied | The user denies the authorization request
invalid_request | The request is missing a required parameter or malformed
invalid_scope | The requested scope is invalid, unknown, or malformed
temporarily_unavailable | The authorization server is currently unable to handle the request

### 2. Exchange the authorization code for an access token

Once the authorization code is obtained, the web app should POST to `https://auth.zjubtv.com/access_token.php` with the following body parameters:

Name | Required | Description
-----|---------|------
appid | Yes | AppID
appsecret | Yes | AppSecret
code | Yes | the authorization_code
grant_type | Yes | use `authorization_code`

An example of request:
```
POST /access_token.php HTTP/1.1
Host: auth.zjubtv.com
Content-Type: application/x-www-form-urlencoded

grant_type=authorization_code&code=tGzv3JOkF0XG5Qx2TlKWIA&appid=s6BhdRkqt3&appsecret=7Fjfp0ZBr1KtDRbnfVdmIw
```

The response body is in json format, for example
```json
{
"access_token":"ACCESS_TOKEN",
"expires_in":3600,
"refresh_token":"REFRESH_TOKEN",
"userid":"USERID",
"scope":"SCOPE"
}
```

Name | Description
-----|------
access_token | the access token for further operation
expires_in | validity time in seconds, 3600 for now
refresh_token | token to get new access_token
userid | a string of the user's id, may be uuid
scope | granted scope, may be a subset of proposed scopes

If something is wrong, the response will be like
```json
{"error": "invalid code"}
```

if the web app wants access to the user's info when the user is offline, the app should at least save userid and refresh_token to its database . The refresh_token and access_token will be invalidated when a user revokes the app's permission on the authorization server.

#### Test access_token (optional)

POST to `https://auth.zjubtv.com/access_token_test.php` with the following parameters:

Name | Description
-----|------
userid | the ID of the user
access_token | access_token
appid | AppID
appsecret | AppSecret

Successful responses have http status code 204.
Failed responses have http status code 403.

#### Get a new access_token with a refresh_token

POST to `https://auth.zjubtv.com/refresh_token.php`. Below is an example

```
POST /refresh_token.php HTTP/1.1
Host: auth.zjubtv.com
Content-Type: application/x-www-form-urlencoded

appid=APPID&grant_type=refresh_token&refresh_token=REFRESH_TOKEN&appsecret=7Fjfp0ZBr1KtDRbnfVdmIw
```

If the old access_token is still valid. The server will not generate a new access_token. While the validity of the old access_token will be extended to 3600 seconds.

Successful responses will be like
```json
{
"access_token":"ACCESS_TOKEN",
"expires_in":3600,
"refresh_token":"REFRESH_TOKEN",
"userid":"USERID",
"scope":"SCOPE"
}
```

Error messages are like
```json
{"error":"invalid refresh_token"}
```


### 3. Get users' basic information

Web apps can get users' basic infomation in json format by POSTING to `https://auth.zjubtv.com/basicuserinfo.php`.

Name | Description
-----|------
userid | the ID of the user
access_token | access_token
appid | AppID
appsecret | AppSecret

A successful responses contains at least the following infomation about the user. More fields will be added in the future.
```json
{
"userid":"USERID",
"realname":"REALNAME",
"is_staff": true or false,
"zjuid": 31300000123,
"email": "xxx@xxx.com"
}
```

## References

1. [Wechat OAuth Manual ](https://wohugb.gitbooks.io/wechat/content/qrconnent/README.html)
2. [OAuth2](http://oauth.net/2/)
3. [OAuth 2 Simplified](https://aaronparecki.com/oauth-2-simplified/)
