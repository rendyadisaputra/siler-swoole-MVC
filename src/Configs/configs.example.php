<?php

declare(strict_types=1);
define('TCP_HOST', '192.168.56.66');
define('TCP_PORT', 9502);

define('MONGODB_HOST', 'mongodb://192.168.56.4:27017');
define('JWTKEY', 'YWVybzI5MTAwMCQxMjN5');
define('FILE_UPLOAD_DIR', __DIR__.'/../../public/images/');
define('URL_HOST', 'http://192.168.56.66:9501/');

define('SMTP_SET', '
{"SMTPDebug" : 1,
    "SMTPAuth"   : true,
    "SMTPSecure" : "tls",
    "Port"       : 587,
    "Host"       : "smtp.gmail.com",
    "Username"   : "email@gmail.com",
    "Password"   : "adfsf@h",
    "email"      : "email@gmail.com",
    "name"       : "your name"
}
');
