# ➢ SWILER ( Swoole + Siler) .::.  High Performance PHP REST Framework Server

I manage to making good Framework with high performance load.

I just like You. Previously I made an application using normal php 7, but the speed and performance did not meet our needs. 

That was the main reason to make good framework without change our love programming language (PHP) but have a best performance over speed , security and stability. 

Here come , Swoole and Siler with complete implementation for production use.

It supporting below: 
- JWT (Json Web Token)
- Cache (Internal Cacher)
- RESTfull, Handling ( GET, POST, REST)
- Easy Routing
- Functional Programming approach instead of OOP
- Database Connection (MongoDB)
- Authentication based On Roles, Rules, & Rules Group

## Environtments
+ MongoDB
+ PHP 7.3+
+ Swoole PHP Extensions

Building REST API Development on top of Swoole + Siler

## How to Run ?
go to command line
```
#> php rest-server.php
```

## Example of REST API
it's using port 9501 by default, If you want to change the port number you can edit by your self in rest-server.php. I put it in `$port` variable, it's easy to find out.

```
GET http://127.0.0.1:9501/members
POST http://127.0.0.1:9501/members
```

## JWT Support
This Framework has a JWT (JSON Web Token ) Support 
you can take a look at `src/emails/templates/index.post.php`

You can use Internal Cacher enable
example API Rest : 

generate token in :
```
POST http://192.168.56.66:9501/auth/guest/login
```

you will get a response like this:

```
{
    "success": true,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTMyMDkzNTksImp0aSI6IjdmZzIzUEpMdUV3SUV0aDl0WGRHTFFYYmdXWFZOR0srQ01EK0JtNU5Od1E9IiwiaXNzIjoic3dvb2xlLWFwaSIsIm5iZiI6MTYxMzIwOTM2MiwiZXhwIjoxNjEzMjk1NzYyLCJkYXRhIjp7InVzZXJOYW1lIjoiZ3Vlc3QtMTYxMzIwOTM1OTE5NDgxNDUzODkiLCJyb2xlcyI6Imd1ZXN0In19.14C45Z3FawN_Fux3lB3dAUl4cATzjRnl0vNoxMnzpRk"
}
```
get the token and send it trought the header 'Authorization'
 
Headers :
```
'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTMyMDkzNTksImp0aSI6IjdmZzIzUEpMdUV3SUV0aDl0WGRHTFFYYmdXWFZOR0srQ01EK0JtNU5Od1E9IiwiaXNzIjoic3dvb2xlLWFwaSIsIm5iZiI6MTYxMzIwOTM2MiwiZXhwIjoxNjEzMjk1NzYyLCJkYXRhIjp7InVzZXJOYW1lIjoiZ3Vlc3QtMTYxMzIwOTM1OTE5NDgxNDUzODkiLCJyb2xlcyI6Imd1ZXN0In19.14C45Z3FawN_Fux3lB3dAUl4cATzjRnl0vNoxMnzpRk'
```

Then use your favorite postman/REST Tool to access it
```
GET http://127.0.0.1:9501/emails/templates/
```

# Where is the class?
Using Folder as a class and file as methods, is it good?

Well... I do agree with you I'm the OOP Player before. 