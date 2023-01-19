
<?php

use App\Server;
use App\Request;
use App\Response;

array_shift($argv);

if (empty($argv)) {
    $port = 80;
} else {
    $port = array_shift($argv);
}


// require "vendor/autoload.php";

require "./vendor/autoload.php";

$server = new Server("127.0.0.1", $port);

$server->listen(function(Request $request){
    return new Response("Hello");
});