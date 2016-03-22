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
 * created by Ivan Saranin <ivan@saranin.com>, on 22.03.2016, at 18:56:28
 */

/* 
 * Module fb-callback
 */

include '../bootstrap.php';
include '../db.php';

// init fb login
$login = new \Groupper\FB\Login(
	$_CONFIG->private->facebook->id,
	$_CONFIG->private->facebook->secret,
	$_CONFIG->private->facebook->version,
	$_CONFIG->private->facebook->permissions
);


$accessToken = $login->getAccessToken();

if (is_string($accessToken)) {
	$newDBToken = new \Groupper\Model\AccessToken();
	$newDBToken->token = $accessToken;
	$newDBToken->time = date(Groupper\FB\Connector::$MYSQL_DATETIME_FORMAT);
	$newDBToken->save();
	
	$currentUrl = sprintf('%s://%s%s', $_SERVER['REQUEST_SCHEME'], $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
	$loginUrl = sprintf('%s/%s',dirname($currentUrl),'login.php');
	header('Location: '.$loginUrl);
} else {
	echo sprintf('Error: %s', $login->lastError);
}
