NGINX Configurator
==================

PHP Library for NGINX configuration parser/generator

![PHP 7.0](https://img.shields.io/badge/PHP-7.0-8C9CB6.svg?style=flat)
[![Build Status](https://travis-ci.org/madkom/nginx-configurator.svg?branch=master)](https://travis-ci.org/madkom/nginx-configurator)
[![Latest Stable Version](https://poser.pugx.org/madkom/nginx-configurator/v/stable)](https://packagist.org/packages/madkom/nginx-configurator)
[![Total Downloads](https://poser.pugx.org/madkom/nginx-configurator/downloads)](https://packagist.org/packages/madkom/nginx-configurator)
[![License](https://poser.pugx.org/madkom/nginx-configurator/license)](https://packagist.org/packages/madkom/nginx-configurator)
[![Coverage Status](https://coveralls.io/repos/github/madkom/nginx-configurator/badge.svg?branch=master)](https://coveralls.io/github/madkom/nginx-configurator?branch=master)
[![Code Climate](https://codeclimate.com/github/madkom/nginx-configurator/badges/gpa.svg)](https://codeclimate.com/github/madkom/nginx-configurator)
[![Issue Count](https://codeclimate.com/github/madkom/nginx-configurator/badges/issue_count.svg)](https://codeclimate.com/github/madkom/nginx-configurator)

---

## Features

This library can parse and generate NGINX configuration files.


## Installation

Install with Composer

```
composer require madkom/nginx-configurator
```

## Requirements

This library requires *PHP* in `~7` version.

## Usage

Parsing configuration string:

```php
<?php

use Madkom\NginxConfigurator\Builder;
use Madkom\NginxConfigurator\Config\Server;
use Madkom\NginxConfigurator\Parser;

require 'vendor/autoload.php';

$config = <<<CONFIG
server {
    listen 8080;
    root /data/www/web;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php;
    }

    error_page 404 /404.html;

    error_page 500 502 503 504 /50x.html;
    location = /50x.html {
        root /usr/share/nginx/www;
    }

    # pass the PHP scripts to FastCGI server listening on the php-fpm socket
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
CONFIG;

$parser = new Parser();
$defaultConfig = $parser->parse($config);
/** @var Server $defaultServers[] */
$defaultServers = $defaultConfig->search(function (Node $node) {
    return $node instanceof Server;
});


$builder = new Builder();
if (count($defaultServers) > 0) {
    /** @var Server $defaultServer */
    foreach ($defaultServers as $defaultServer) {
        $builder->appendServerNode($defaultServer);
    }
}
```

Generating configuration string:

```php
<?php

use Madkom\NginxConfigurator\Builder;
use Madkom\NginxConfigurator\Config\Location;
use Madkom\NginxConfigurator\Node\Directive;
use Madkom\NginxConfigurator\Node\Literal;
use Madkom\NginxConfigurator\Node\Param;

require __DIR__ . '/../vendor/autoload.php';

$builder = new Builder();

$server = $builder->addServerNode(80);
$server->append(new Directive('error_log', [new Param('/var/log/nginx/error.log'), new Param('debug')]));
$server->append(new Location(new Param('/test'), null, [
    new Directive('error_page', [new Param('401'), new Param('@unauthorized')]),
    new Directive('set', [new Param('$auth_user'), new Literal('none')]),
    new Directive('auth_request', [new Param('/auth')]),
    new Directive('proxy_pass', [new Param('http://test-service')]),
]));
$server->append(new Location(new Param('/auth'), null, [
    new Directive('proxy_pass', [new Param('http://auth-service:9999')]),
    new Directive('proxy_bind', [new Param('$server_addr')]),
    new Directive('proxy_redirect', [new Param('http://$host'), new Param('https://$host')]),
    new Directive('proxy_set_header', [new Param('Content-Length'), new Literal("")]),
    new Directive('proxy_pass_request_body', [new Param('off')]),
]));
$server->append(new Location(new Param('@unauthorized'), null, [
    new Directive('return', [new Param('302'), new Param('/login?backurl=$request_uri')]),
]));
$server->append(new Location(new Param('/login'), null, [
    new Directive('expires', [new Param('-1')]),
    new Directive('proxy_pass', [new Param('http://identity-provider-service')]),
    new Directive('proxy_bind', [new Param('$server_addr')]),
    new Directive('proxy_redirect', [new Param('http://$host'), new Param('https://$host')]),
    new Directive('proxy_set_header', [new Param('Content-Length'), new Literal("")]),
    new Directive('proxy_pass_request_body', [new Param('off')]),
]));

print($builder->dump());
```

Generated configuration output:

```
server {
        listen 80;
        listen [::]:80 default ipv6only=on;
        error_log /var/log/nginx/error.log debug;
        location  /test {
                error_page 401 @unauthorized;
                set $auth_user "none";
                auth_request /auth;
                proxy_pass http://test-service;
        }
        
        location  /auth {
                proxy_pass http://auth-service:9999;
                proxy_bind $server_addr;
                proxy_redirect http://$host https://$host;
                proxy_set_header Content-Length "";
                proxy_pass_request_body off;
        }
        
        location  @unauthorized {
                return 302 /login?backurl=$request_uri;
        }
        
        location  /login {
                expires -1;
                proxy_pass http://identity-provider-service;
                proxy_bind $server_addr;
                proxy_redirect http://$host https://$host;
                proxy_set_header Content-Length "";
                proxy_pass_request_body off;
        }
        
}
```

There are also methods to read and dump file:

```php

use Madkom\NginxConfigurator\Builder;
use Madkom\NginxConfigurator\Config\Location;
use Madkom\NginxConfigurator\Config\Server;
use Madkom\NginxConfigurator\Node\Directive;
use Madkom\NginxConfigurator\Node\Literal;
use Madkom\NginxConfigurator\Parser;

require __DIR__ . '/../vendor/autoload.php';

$parser = new Parser();
$builder = new Builder();

$configuration = $parser->parseFile('default.conf');

/** @var Server $servers[] */
$servers = $configuration->search(function (Node $node) {
    return $node instanceof Server;
});
if (count($servers) > 0) {
    /** @var Server $server */
    foreach ($servers as $server) {
        $builder->appendServerNode($server);
    }
}

$builder->dumpFile('generated.conf');
```

## TODO

* [ ] Implement comments parsing

## License

The MIT License (MIT)

Copyright (c) 2016 Madkom S.A.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.