<?
/* 
 * Test module api-connect-test
 * 
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
//
//$r = $fb->request('GET', '/me/permissions');
//
//var_dump($r);
//var_dump($fb->lastError);

///
//$new = $fb->getGroupFromUrl('https://www.facebook.com/groups/testasdmygroup/');
//var_dump($new);
//var_dump($fb->lastError);

//$r = $fb->createPost('1081671058564480', 'asd', '');
$args = [];
$args["message"] = "abc";
$args["caption"] = "2This is caption!";
$args["description"] = "This is description!";
$args["name"] = "This is name!";
$args["url"] = //"http://www.rdiconnect.com/wp-content/uploads/2014/11/asd-research-study.png";
		"http://www.advicio-servdesk.de/asd_wordpress_prod/wp-content/gallery/ci-logo/asd_harmony_600x600.jpg";

//$args['object'] = json_encode($args);

//$r = $fb->request('POST', '/1081671058564480/objects/product', $args);
//$r = $fb->request('GET', '/1081671058564480/product_groups');
$r = $fb->addPhoto('1081671058564480', 'testtest', 'groupper.jpg');

var_dump($r);
var_dump($fb->lastError);