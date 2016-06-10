<?php

use Madkom\NginxConfigurator\Builder;
use Madkom\NginxConfigurator\Config\Server;
use Madkom\NginxConfigurator\Node\Node;
use Madkom\NginxConfigurator\Parser;

require __DIR__ . '/../vendor/autoload.php';

$config = <<<CONFIG
server {
    listen 8080;
    root /data/www/web;
    index index.php index.html index.htm;

    location / {
        try_files \$uri \$uri/ /index.php;
    }

    error_page 404 /404.html;

    error_page 500 502 503 504 /50x.html;
    location = /50x.html {
        root /usr/share/nginx/www;
    }

    # pass the PHP scripts to FastCGI server listening on the php-fpm socket
    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
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
