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
 * created by Ivan Saranin <ivan@saranin.com>, on 23.03.2016, at 21:49:23
 */

/* 
 * Module dummy
 */

include '../bootstrap.php';
include '../db.php';

var_dump(microtime(false));

list($microseconds, $seconds) = explode(' ', microtime(false));

$microseconds = $seconds . substr($microseconds, 1, 7);

var_dump($microseconds);

$curDateTime = \DateTime::createFromFormat('U.u', $microseconds);

var_dump($curDateTime->format('H:i:s:u'));
