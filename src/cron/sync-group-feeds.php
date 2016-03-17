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
 * created by Ivan Saranin <ivan@saranin.com>, on 16.03.2016, at 0:04:27
 */

/* 
 * Module sync-group-feeds
 * 
 * Cron job for syncing facebook group feeds with db
 */

include '../bootstrap.php';

// initialize database object
$db = new MysqliDb\MysqliDb(
	$_CONFIG->private->db->host,
	$_CONFIG->private->db->user,
	$_CONFIG->private->db->password,
	$_CONFIG->private->db->base,
	null,
	$_CONFIG->private->db->charset
);

// initialize facebook object
$fb = new Groupper\FB\Connector(
	$_CONFIG->private->facebook->id,
	$_CONFIG->private->facebook->secret,
	$_CONFIG->private->facebook->version
);




$fb->connect($_CONFIG->private->facebook->accessToken);

$r = $fb->request('GET', '/me');
var_dump($r);
var_dump($r->getDecodedBody());