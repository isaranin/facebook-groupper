<?
/* 
 * Test module api-connect-test
 * 
 */

include '../bootstrap.php';

$fb = new Groupper\FB\Login(
	$_CONFIG->private->facebook->id,
	$_CONFIG->private->facebook->secret,
	$_CONFIG->private->facebook->version,
	$_CONFIG->private->facebook->permissions
);
var_dump($fb->getLoginUrl('asd'));