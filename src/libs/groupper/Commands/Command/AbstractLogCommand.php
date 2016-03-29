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
 * created by Ivan Saranin <ivan@saranin.com>, on 29.03.2016, at 11:47:00
 */

namespace Groupper\Commands\Command;

/*
 * Module for AbstractLogCommand class
 */

abstract class AbstractLogCommand extends AbstractFbCommand {
	/**
	 * Log
	 * 
	 * @var \SA\Log\AbstractLog
	 */
	protected $log = null;
	
	/**
	 * Check if log is not null
	 * @param type $params
	 */
	protected function innerExecute($params) {
		$res = parent::innerExecute($params);
		if (is_string($res)) {
			return $res;
		}
		if (is_null($this->log)) {
			return 'You should execute init method first, Log can`t be null.';
		}		
		return true;
	}
	
	/**
	 * Init command with log
	 * 
	 * @param \MysqliDb\MysqliDb $db mysql db
	 * @param \Groupper\FB\Connector $fb facebook connector
	 * @param \SA\Log\AbstractLog $log log 
	 */
	public function init() {
		parent::init();
		if (func_num_args() > 2) {
			$this->log = func_get_arg(2);		
		}
	}
}
