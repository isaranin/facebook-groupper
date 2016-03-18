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

//$group = new \Groupper\Model\Group();
////$a = $group->ArrayBuilder()->orderBy("id", "desc")->get();
//$a = $group->byId(2);
//var_dump($a);
//
//$feeds = new \Groupper\Model\FeedItem();
//$a = $feeds->byId(['postid'=>1, 'groupid'=>1]);
//var_dump($a);

//$collection = \Groupper\Model\Collection::join('\Groupper\Model\Group', 'group_id');
//var_dump($collection->get());
//var_dump($collection);

//$group = new \Groupper\Model\Group();
//$group->url = 'https://www.facebook.com/groups/Phuketbuysellrent/';
//$group->syncfeed = true;
//$a = $group->save();
//var_dump($a);
//print_r($group->errors);

$group = \Groupper\Model\Group::byId(2);
//$a = $group->ArrayBuilder()->orderBy("id", "desc")->get();
$group->syncfeed = false;
$group->save();
var_dump($group);