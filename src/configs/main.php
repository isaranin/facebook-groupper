<?

/* 
 * facebook-groupper
 * https://github.com/isaranin/facebook-groupper
 *  
 * Worker with facebook groups
 *  
 * repository		git@github.com:isaranin/facebook-groupper.git
 *  
 * author		Ivan Saranin
 * company		Saranin Co.
 * url			http://saranin.co
 * copyright		(c) 2016, Saranin Co.
 *  
 *  
 * created by Ivan Saranin <ivan@saranin.com>, on 11.03.2016, at 12:17:30
 */

/* 
 * Module main
 * 
 * Main config file
 */

$_CONFIG = new \stdClass();

$_CONFIG->main = (object)[
    'dev' => true,
	'timezone' => 'UTC',
	'baseDir' => dirname(__DIR__)
];

$_CONFIG->log = (object)[
	'main' => $_CONFIG->main->baseDir.'/logs/main/%Y/%m/%d.txt',
	'cron' => $_CONFIG->main->baseDir.'/logs/cron/%Y/%m/%d.txt',
	'feed' => $_CONFIG->main->baseDir.'/logs/feed/%Y/%m/%d.txt',
	'maxSize' => -1
];

$_CONFIG->private = include('my.private.php');

