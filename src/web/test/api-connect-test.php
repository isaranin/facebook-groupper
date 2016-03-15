<?
/* 
 * Test module api-connect-test
 * 
 */

include '../bootstrap.php';

$fb = new Groupper\FB\Connector(
	$_CONFIG->private->facebook->id,
	$_CONFIG->private->facebook->secret,
	$_CONFIG->private->facebook->version
);

$fb->connect($_CONFIG->private->facebook->accessToken);

$r = $fb->request('GET', '/me');
var_dump($r);
var_dump($r->getDecodedBody());