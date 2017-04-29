<?php

//ob_start('compressHTMLOutput');

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

try {
	$db = Zend_Db::factory('Pdo_Mysql',  array(
			'host'     => '127.0.0.1',
			'username' => 'root',
			'password' => '',
			'dbname'   => 'zf1-shopcart'
	));
	$db->getConnection();
} catch (Zend_Db_Adapter_Exception $e) {
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Database Connection Error</title>
		</head>
		<body>
			<h3>Error connecting database</h3>
			<p>One of these errors has occurred:</p>
			<ul>
				<li>Database is down</li>
				<li>Wrong connection string to the database</li>				
			</ul>
		</body>
	</html>
	<?php
	exit;
} catch (Zend_Exception $e) {
	echo "loading db adapter failed :("; exit;
}

$application->bootstrap()
            ->run();

/* compress HTML output */
// ob_end_flush();
function compressHTMLOutput($buffer)
{
	$bufferout = $buffer;
	$bufferout = str_replace("\n", "", $bufferout);
	$bufferout = str_replace("\t", "", $bufferout);
	$bufferout = preg_replace('/<!--(.|\s)*?-->/', '', $bufferout);
	return $bufferout;
}