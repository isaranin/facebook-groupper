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
 * created by Ivan Saranin <ivan@saranin.com>, on 23.03.2016, at 19:36:58
 */

/* 
 * Module cron
 * 
 */

include '../bootstrap.php';
include '../db.php';

if (php_sapi_name() !== 'cli') {
	exit(0);
}

set_time_limit(0);

// initialize facebook group object
$fbGroup = new Groupper\FB\Group(
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

// connecting to fb
$newToken = $fbGroup->connect($lastToken);

// save new token
if (is_string($newToken)) {
	$newDBToken = new \Groupper\Model\AccessToken();
	$newDBToken->token = $newToken;
	$newDBToken->save();
}

// command execute process
$tasks = \Groupper\Model\CronItem::ObjectBuilder()->get();
if (!is_array($tasks)) {
	exit(1);
}

$now = time();

$commandManager = new \Groupper\Commands\Manager();
// run throw tasks and execute them
foreach($tasks as $task) {
	if ($task->enabled) {
		$startTime = new \DateTime($task->start);
		if ($startTime->getTimestamp() < $now) {
			if (!is_null($task->lastexec)) {
				$lastExec = new \DateTime($task->lastexec);
				// add last time executed to interval (calculated in minutes)
				// check it its not time to execute command, take next
				if ($lastExec->getTimestamp()+$task->interval*60 > $now) {
					continue;
				}
			}
			$command = $commandManager->getCommandByName($task->command);
			
			if ($command === false) {
				// write error to log
			}
			$command->init($db, $fbGroup);
			$command->execute($task->params);
			$task->lastexec = date(\Groupper\FB\Connector::$MYSQL_DATETIME_FORMAT, $now);
			$task->save();
		}
	}
}
