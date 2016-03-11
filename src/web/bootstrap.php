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
 * created by Ivan Saranin <ivan@saranin.com>, on 11.03.2016, at 12:16:01
 */

/* 
 * Module bootstrap
 * Preboot module
 */

require_once __DIR__.'/configs/main.php';
require_once __DIR__.'/libs/autoload.php';

error_reporting(E_ALL);

// set timezone
date_default_timezone_set($_CONFIG->main->timezone);

// set error
ini_set('display_errors', ($_CONFIG->main->dev?'1':'0'));