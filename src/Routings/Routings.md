# Routing Architectures

**Examples :**
method `get` in path `/members/`
method `post` in path `/members/`
method `get` in path `/articles/`
method `post` in path  `/articles/`

`GET /members` => `Routings/members/index.get.php`
`POST /members` => `Routings/members/index.post.php`

structures should be like this

```
+ root
-- + routings
---- + members
------- + index.get.php
------- + index.post.php
---- + articles
------- + index.get.php
------- + index.post.php
```

---

if you want to filtering query, please use _**query string**_
**e.g** : 
```
key1 = val1
key2 = val2
key3 = val3
```
how to use
```
<path>/?key1=val1&key2=val2&key3=val3
```

