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
 * created by Ivan Saranin <ivan@saranin.com>, on 23.03.2016, at 19:27:46
 */

namespace Groupper\Model;

/*
 * Module for CronItem class
 */

class CronItem extends \MysqliDb\dbObject {
	
	protected $dbTable = 'cron';
	
	protected $primaryKey = 'id';
	
	protected $dbFields = [
		'command' => ['text', 'required'],
		'params' => ['text'],
		'interval' => ['int', 'required'],
		'start' => ['datetime', 'required'],
		'lastexec' => ['datetime'],
		'enabled' => ['bool', 'required'],
		'updated' => ['datetime', 'required'],
		'created' => ['datetime', 'required'],
	];
	
	protected $updateField = 'updated';
	protected $createField = 'created';
	
	protected $jsonFields = ['params'];
}
