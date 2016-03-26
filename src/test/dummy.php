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

$fb = new \Groupper\FB\Group(
	$_CONFIG->private->facebook->id,
	$_CONFIG->private->facebook->secret,
	$_CONFIG->private->facebook->version
);

// find last facebook token
$lastDBToken = \Groupper\Model\AccessToken::ObjectBuilder()->orderBy('time')->getOne();

if (!is_null($lastDBToken)) {
	$lastToken = $lastDBToken->token;
} else {
	$lastToken = $_CONFIG->private->facebook->accessToken;
}

$fb->connect($lastToken);

$manager = new \Groupper\Commands\Manager();

$a = $manager->getCommandByName('createpost');
$a->init($db, $fb);
var_dump($a->execute(['group_id' => '1700121430243230', 'post_id' => '1']));
var_dump($a->lastError);