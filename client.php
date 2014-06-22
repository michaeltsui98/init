<?php
error_reporting(E_ALL);

require_once __DIR__ . "/lib/php/lib/Thrift/ClassLoader/ThriftClassLoader.php";
use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = realpath(dirname(__FILE__) . "/gen-php");

$loader = new ThriftClassLoader();
$loader->registerNamespace("Thrift", __DIR__ . "/lib/php/lib");
$loader->registerDefinition("tService", $GEN_DIR);
$loader->register();

use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Protocol\TBinaryProtocol;

try {
//    $socket = new TSocket('127.0.0.1', 80,'/init/phpServer.php');
    $socket = new THttpClient('127.0.0.1', 80,'/init/phpServer.php');
    $transport = new TBufferedTransport($socket);
    $protocol = new TBinaryProtocol($transport);
    $client = new \tService\UserServiceClient($protocol);
    
    $transport->open();
    
    $userName = $client->getUserNameById(25);
    
    echo $userName;
    $transport->close();
} catch (Exception $e) {
    echo $e->getMessage();
}