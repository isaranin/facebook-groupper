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

$log = new SA\Log\File($_CONFIG->log->cron, $_CONFIG->log->maxSize);

$log->addDelimiter('Execute new cron job');

// initialize facebook group object
$fbGroup = new Groupper\FB\Group(
	$_CONFIG->private->facebook->id,
	$_CONFIG->private->facebook->secret,
	$_CONFIG->private->facebook->version
);

$log->put('Looking for fb token...');
// find last facebook token
$lastDBToken = \Groupper\Model\AccessToken::ObjectBuilder()->orderBy('time')->getOne();

if (!is_null($lastDBToken)) {
	$lastToken = $lastDBToken->token;
	$log->put('Token finded in DB');
} else {
	$lastToken = $_CONFIG->private->facebook->accessToken;
	$log->put('Token finded in config');
}

$log->put('Connecting to FB Graph API...');
// connecting to fb
$newToken = $fbGroup->connect($lastToken);

// save new token
if (is_string($newToken)) {
	$newDBToken = new \Groupper\Model\AccessToken();
	$newDBToken->token = $newToken;
	$newDBToken->save();
	$log->put('New token finded and saved');
}
$log->put('Connected!');

$log->put('Getting cron tasks...');
// command execute process
$tasks = \Groupper\Model\CronItem::ObjectBuilder()->get();
if (!is_array($tasks)) {
	$log->put('Error gettingg tasks');
	exit(1);
}
$log->pput('Finded %s tasks', count($tasks));
$now = time();

$commandManager = new \Groupper\Commands\Manager();
// run throw tasks and execute them
foreach($tasks as $task) {
	$log->pput('Check task %s', $task->id);
	if ($task->enabled) {
		$log->put('Task enabled');
		$startTime = new \DateTime($task->start);
		$log->put('Start time: ', $startTime->format('c'));
		if ($startTime->getTimestamp() < $now) {
			$log->put('Last time execute: ', $task->lastexec);
			if (!is_null($task->lastexec)) {
				$lastExec = new \DateTime($task->lastexec);				
				// add last time executed to interval (calculated in minutes)
				// check it its not time to execute command, take next
				$executeTime = $lastExec->getTimestamp()+$task->interval*60;
				if ($executeTime > $now) {
					$log->pput(
							'Time not come, need to wait %s', 
							date('z \d\a\y\s H \h\o\u\r\s m \m\i\n\u\t\e\s', $executeTime-$now)
					);
					continue;
				}
			}
			$command = $commandManager->getCommandByName($task->command);
			
			if ($command === false) {
				$log->pput('Can`t find processor for command %s ',$task->command);
				continue;
			}
			$command->init($db, $fbGroup, $log);
			$log->pput('Executing command %s ...',$task->command);
			$res = $command->execute($task->params);
			if ($res === false) {
				$log->put($command->lastError);
			} 
			$log->put('Finish execute command!');
			$task->lastexec = date(\Groupper\FB\Connector::$MYSQL_DATETIME_FORMAT, $now);
			$task->save();
		}
	}
}
$log->addDelimiter('Finish!');