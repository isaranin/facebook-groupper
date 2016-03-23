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
 * created by Ivan Saranin <ivan@saranin.com>, on 23.03.2016, at 20:18:10
 */

namespace Groupper\Commands\Command;

/*
 * Module for AbstractFbCommand class
 */

abstract class AbstractFbCommand extends AbstractCommand {
	
	/**
	 * Database
	 * 
	 * @var \MysqliDb\MysqliDb 
	 */
	public $db = null;
	
	/**
	 * Facebook connector class
	 * @var \Groupper\FB\Connector 
	 */
	public $fb = null;
	
	/**
	 * Check if facebook is connected and db is not null
	 * @param type $params
	 */
	protected function innerExecute($params) {
		if (is_null($this->db)) {
			return 'You should execute init method first, MysqlDB can`t be null.';
		}
		if (is_null($this->fb)) {
			return 'You should execute init method first, Facebook can`t be null.';
		}
		if (!$this->fb->connected) {
			return 'Connect facebook first, execute FB\Connector->connect() method.';
		}
		return true;
	}
	
	/**
	 * Init Facebook command
	 * 
	 * @param \MysqliDb\MysqliDb $db mysql db
	 * @param \Groupper\FB\Connector $fb facebook connector
	 */
	public function init() {
		if (func_num_args() > 1) {
			$this->db = func_get_arg(0);
			$this->fb = func_get_arg(1);		
		}
	}
}
