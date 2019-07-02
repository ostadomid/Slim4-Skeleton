<?php
/**
 * This file is in autoloader/files section of composer.json file 
 * and is loaded after all psr4 namespaces have been loaded so there is no 
 * need to add autoload.php file of composer !
 */

/**
 * Load .env file
 */
$dotenv = \Dotenv\Dotenv::create(__DIR__.'./../'); // the path to .env file
$dotenv->load();

/**
 * Boot Eloquent
 */

use Illuminate\Database\Capsule\Manager as Capsule;


$capsule = new Capsule;

/**
 * env function is defined in illuminate package in file helpers.php
 * so we have access to it here and everywhere in global namespace!
 */
$driver   = env('driver','mysql');
$host     = env('host','localhost');
$database = env('database','school');
$username = env('username','root');
$password = env('password','');

$capsule->addConnection([
    'driver'    => $driver,
    'host'      => $host,
    'database'  => $database,
    'username'  => $username,
    'password'  => $password,
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
$capsule->setEventDispatcher(new Dispatcher(new Container));

// // Make this Capsule instance available globally via static methods... (optional)
 $capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();