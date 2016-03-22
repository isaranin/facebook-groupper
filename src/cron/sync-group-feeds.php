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
include '../db.php';

// initialize facebook group object
$fbGroup = new Groupper\FB\Group(
	$_CONFIG->private->facebook->id,
	$_CONFIG->private->facebook->secret,
	$_CONFIG->private->facebook->version
);

// find last facebook token
$lastDBToken = \Groupper\Model\AccessToken::ArrayBuilder()->orderBy('time')->getOne();

if (!is_null($lastDBToken)) {
	$lastToken = $lastDBToken->token;
} else {
	$lastToken = $_CONFIG->private->facebook->accessToken;
}

// connecting to fb
$newToken = $fbGroup->connect($lastToken);

// save new token
if (is_string($newToken)) {
	$newDBToken = new \Groupper\Model\AccessToken();
	$newDBToken->token = $newToken;
	$newDBToken->time = date(Groupper\FB\Connector::$MYSQL_DATETIME_FORMAT);
	$newDBToken->save();
}

// take groups
$groups = \Groupper\Model\Group::ObjectBuilder()->get();
if (empty($groups)) {
	return 0;
}

// update groups if they have only url
foreach($groups as $group) {
	if (empty($group->code) && !empty($group->url)) {
		$newGroupData = $fbGroup->getGroupFromUrl($group->url);
		if (is_array($newGroupData)) {
			$group->save($newGroupData);
		}
	}
}

// take last feeds from groups
foreach($groups as $group) {
	if (!empty($group->id) && $group->syncfeed) {
		
		// take 25 last posts, facebook sort it by post update, not created date
		$feed = $fbGroup->feed($group->id, null, 25);
		if (!is_array($feed)) {
			// log error
			continue;
		}
		
		// save each feed post
		foreach($feed as $feedItem) {
			$dbFeedItem = \Groupper\Model\FeedItem::byId([
				'groupid' => $feedItem['groupid'],
				'postid' => $feedItem['postid']
				]
			);
			if (is_null($dbFeedItem)) {
				$dbFeedItem = new \Groupper\Model\FeedItem($feedItem);
				$res = $dbFeedItem->save();
			} else {
				$res = $dbFeedItem->update($feedItem);
			}
			
			// log error			
		}
		
		// save last sync time to group
		$group->lastsync = date(Groupper\FB\Connector::$MYSQL_DATETIME_FORMAT);
		$group->save();
	}
}
