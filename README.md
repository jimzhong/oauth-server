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
redirect_uri | Yes | must match one redirect_uri registered for this app
response_type | Yes | use `code` for now, may support `token` in the future
scope | Yes | space delimited authorization scopes, currently only `baiscuserinfo.readonly`
state | Recommend | for CSRF protection, will be copied to redirect_uri
access_type | No | default to `online` access, may support `offline` in the future
include_granted_scopes | No | If this is provided with the value `true`, and the authorization request is granted the authorization will include any previous authorizations granted to this user/application combination for other scopes. Default to `false`. Incremental scopes are not supported for now.

On the authorization page, the user will be prompted only the first time your app requests access.

If the user gives permission to this app, the authorization endpoint will redirect the user to `REDIRECT_URI?code=CODE&state=STATE`. The `REDIRECT_URI` is the redirect URI registered for that app in the database.

If anything goes wrong, the user agent will be redirected to `REDIRECT_URI?error=ERRORMSG`. The possible values of `error` is defined in the RFC, common values are:

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
redirect_uri | Yes | must match the redirect_uri used for getting the code

An example of request:
```
POST /access_token.php HTTP/1.1
Host: auth.zjubtv.com
Content-Type: application/x-www-form-urlencoded

grant_type=authorization_code&code=tGzv3JOkF0XG5Qx2TlKWIA&appid=s6BhdRkqt3&appsecret=7Fjfp0ZBr1KtDRbnfVdmIw
```

The lifetime of an authorization_code is only 300 seconds and an authorization_code can only be used once.

The response body is in json format, for example
```json
{
  "access_token":"1/fFAGRNJru1FTz70BzhT3Zg",
  "expires_in":3920,
  "user_id": 20
}
```

Name | Description
-----|------
access_token | the access token for further operation
expires_in | validity time in seconds, 3600 for now
refresh_token | only present if `access_type=offline` is included in the authorization code request
user_id | user's identifier

If something is wrong, the response will be like
```json
{"error": "invalid code"}
```

If the web app wants access to the user's info when the user is offline, the app should use `access_type=offline`. And save the refresh_token to its database . The refresh_token will be invalidated when a user revokes the app's permission on the authorization server. Offline access is not implemented for now.

#### Test access_token (optional)

POST to `https://auth.zjubtv.com/access_token_test.php` with the following parameters:

Name | Description
-----|------
userid | the ID of the user
access_token | access_token
appid | AppID

Successful responses have http status code 200 with a body saying OK.
Failed responses have http status code 401 with a body saying unauthorized.

#### Get a new access_token with a refresh_token (not implemented for now)

POST to `https://auth.zjubtv.com/refresh_token.php`. Below is an example

```
POST /refresh_token.php HTTP/1.1
Host: auth.zjubtv.com
Content-Type: application/x-www-form-urlencoded

appid=APPID&grant_type=refresh_token&refresh_token=REFRESH_TOKEN&appsecret=7Fjfp0ZBr1KtDRbnfVdmIw
```

Successful responses will be like
```json
{
"access_token":"ACCESS_TOKEN",
"expires_in": EXPIRE_TIME,
"refresh_token":"REFRESH_TOKEN",
"userid":"USERID",
"scope":"SCOPE"
}
```

If there is a valid access_token, the `EXPIRE_TIME` reflects its remaining time, and the current access_token is return. Otherwise, a new access_token is generated and returned, with the `EXPIRE_TIME` set to 3600.

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

A successful responses contains at least the following infomation about the user. More fields will be added in the future.
```json
{
"userid":"USERID",
"realname":"REALNAME",
"is_staff": true or false,
"is_admin": true or false,
"zjuid": 31300000123,
"email": "xxx@xxx.com"
}
```

## The length of parameters

Name | Max length in ASCII characters
-----|------------------
App ID| 16
App Secret | 64
Authorization Code | 64
Access Token | 64
Refresh Token | 64
User ID | 16

## References

1. [Wechat OAuth Manual ](https://wohugb.gitbooks.io/wechat/content/qrconnent/README.html)
2. [OAuth2](http://oauth.net/2/)
3. [OAuth2 Simplified](https://aaronparecki.com/oauth-2-simplified/)
4. [OAuth2 for Server-side Web Apps](https://developers.google.com/identity/protocols/OAuth2WebServer)
