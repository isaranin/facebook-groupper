<?
/* 
 * Module db-connect-test
 */
include '../bootstrap.php';

$db = new MysqliDb\MysqliDb(
		$_CONFIG->private->db->host,
		$_CONFIG->private->db->user,
		$_CONFIG->private->db->password,
		$_CONFIG->private->db->base,
		null,
		$_CONFIG->private->db->charset);

$group = new \Groupper\Model\Group();
//$a = $group->ArrayBuilder()->orderBy("id", "desc")->get();
$a = $group->byId(2);
var_dump($a);

$feeds = new \Groupper\Model\FeedItem();
$a = $feeds->byId(['postid'=>1, 'groupid'=>1]);
var_dump($a);