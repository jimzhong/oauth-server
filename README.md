OAuth 2.0 Server
======================

## Background

OAuth 2.0 is a protocol for authorization. There is a centeral authorization server and various web apps. Each app has a unique AppID and an AppSecret. When a user want to use one web app. The user will be redirected to a page at the authorization server. If the user is authenticated. The user will be prompted to give permission to the app. If permission is granted, the authorization server will redirect the user to a URL on the web app with a special code. The web app can then get the user's information by sending requests to the authorization server with its AppID, AppSecret and the code.

## Flow

### 1. Get the code (授权临时票据)

redirect the user to `https://auth.zjubtv.com/connect?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE`

Name | Required | Description
-----|---------|------
appid| Yes  | AppID
redirect_uri | Yes | UrlEncoded URI for redirection
response_type | Yes | use `code`
scope | Yes | authorization scope, currently only `baiscuserinfo`
state | No | for CSRF protection, will be copied to redirect_uri

If the user gives permission to this app, the authorization server will redirect the user to `redirect_uri?code=CODE&state=STATE` and thus the web app can get the code.

### 2. Acquire access token

Given the code, the web app should POST to `https://auth.zjubtv.com/access_token` with the following body parameters:

Name | Required | Description
-----|---------|------
appid | Yes | AppID
appsecret | Yes | AppSecret
code | Yes | the code in the user's GET requests
grant_type | Yes | currently `authorization_code`

An example of request:
```
POST /access_token HTTP/1.1
Host: auth.zjubtv.com
Content-Type: application/x-www-form-urlencoded
grant_type=authorization_code&code=tGzv3JOkF0XG5Qx2TlKWIA&appid=s6BhdRkqt3&appsecret=7Fjfp0ZBr1KtDRbnfVdmIw
```

The response body is in json format, for example
```json
{
"access_token":"ACCESS_TOKEN",
"expires_in":7200,
"refresh_token":"REFRESH_TOKEN",
"userid":"USERID",
"scope":"SCOPE"
}
```

If something is wrong, the response will be like
```json
{"errcode":40029,"errmsg":"invalid code"}
```

Name | Description
-----|------
access_token | the access token for further operation
expires_in | validity time
refresh_token | token to get new access_token
userid | a string of the user's id, may be uuid
scope | authorization scope, currently only `baiscuserinfo`

The web app should at least save userid and refresh_token to its database if it wants access to the user's info even when the user is not using the service. The refresh_token and access_token will be invalidated when a user revokes the app's permission on the authorization server.

#### Test access_token (optional)

GET `https://auth.zjubtv.com/auth?userid=USERID`. The `Authorization` header should be set to `Bearer ACCESS_TOKEN`.

Successful responses are like
```json
{ "errcode":0,"errmsg":"ok" }
```

Failed responses are like
```json
{ "errcode":40003,"errmsg":"invalid userid" }
```

#### Get a new access_token with a refresh_token

POST `https://auth.zjubtv.com/refresh_token`. Below is an example

```
POST /refresh_token HTTP/1.1
Host: auth.zjubtv.com
Content-Type: application/x-www-form-urlencoded
appid=APPID&grant_type=refresh_token&refresh_token=REFRESH_TOKEN&appsecret=7Fjfp0ZBr1KtDRbnfVdmIw
```

Successful responses will be like
```json
{
"access_token":"ACCESS_TOKEN",
"expires_in":7200,
"refresh_token":"REFRESH_TOKEN",
"userid":"USERID",
"scope":"SCOPE"
}
```

Error messages are like
```json
{"errcode":40030,"errmsg":"invalid refresh_token"}
```

### 3. Get users' basic information

Web apps can get users' basic infomation in json format by GETTING `https://auth.zjubtv.com/basicuserinfo?userid=USERID`, the `Authorization` header should be set to `Bearer ACCESS_TOKEN`.

A successful responses contains at least the following infomation about the user. More fields will be added in the future.
```json
{
"userid":"USERID",
"nickname":"NICKNAME",
"realname":"REALNAME"
}
```

## References

1. [Wechat OAuth Manual ](https://wohugb.gitbooks.io/wechat/content/qrconnent/README.html)
2. [OAuth2](http://oauth.net/2/)
