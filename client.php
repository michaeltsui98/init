<?php
error_reporting(E_ALL);
$benchmark = new Cola_Com_Benchmark();
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
    
    $param = 1;
    if(isset($argv[1])){
    	$param = $argv[1];
    }
    
    $userName = $client->getUserNameById($param);
    
    echo $userName;
    $transport->close();
} catch (Exception $e) {
    echo $e->getMessage();
} 

echo "<br />cost:", $benchmark->cost (), 's';

class Cola_Com_Benchmark
{

	protected $_time = array();

	public function __construct()
	{
		$this->start();
	}

	public function start($mark = 'start')
	{
		$this->_time = array();
		return $this->mark($mark);
	}

	public function end($mark = 'end')
	{
		return $this->mark($mark);
	}

	public function mark($name = null)
	{
		if (is_null($name)) {
			return $this->_time[] = microtime(true);
		} else {
			return $this->_time[$name] = microtime(true);
		}
	}

	public function cost($p1 = 'start', $p2 = 'end', $decimals = 4)
	{
		$t1 = (empty($this->_time[$p1])) ? $this->mark($p1) : $this->_time[$p1];
		$t2 = (empty($this->_time[$p2])) ? $this->mark($p2) : $this->_time[$p2];

		return abs(number_format($t2 - $t1, $decimals));
	}

	public function step($decimals = 4)
	{
		$t1 = end($this->_time);
		$t2 = $this->mark();
		return number_format($t2 - $t1, $decimals);
	}

	public function time()
	{
		return $this->_time;
	}

	/**
	 * Get the amount of memory allocated to PHP
	 *
	 * Set $flag to TRUE to get the real size of memory allocated from system.
	 * If not set or FALSE only the memory used by emalloc() is reported.
	 *
	 * @param boolean $flag
	 * @return int
	 */
	public function memory($flag = false)
	{
		return memory_get_usage($flag);
	}

}