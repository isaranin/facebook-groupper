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
 * created by Ivan Saranin <ivan@saranin.com>, on 23.03.2016, at 20:13:50
 */

namespace Groupper\Commands\Command;

/*
 * Module for AbstractCommand class
 */
abstract class AbstractCommand {
	
	/**
	 * Extended class should add here command text, as it used outside
	 * Should be always in lower case	 * 
	 * @var string
	 */
	protected $command = '';
	
	/**
	 * Last error text
	 * @var string
	 */
	public $lastError = '';
	
	/**
	 * Method for checking input parametres
	 * If error happens, should return string with error text
	 */
	abstract protected function checkParams($params);
	
	/**
	 * This method should be overwritten
	 * Command execution process should be here
	 * If error happens, should return string with error text
	 */
	abstract protected function innerExecute($params);
	
	/**
	 * Method for init object
	 */
	abstract public function init();
	
	/**
	 * Method for execute command
	 * 
	 * @param array $params
	 * @return boolean 
	 */
	public function execute($params) {
		$params = (array)$params;
		$check = $this->checkParams($params);
		if (is_string($check)) {
			$this->lastError = sprintf('Wrong parametres: %s', $check);
			return false;
		}
		
		$res = $this->innerExecute($params);
		if (is_string($res)) {
			$this->lastError = sprintf('Error execution: %s', $res);
			return false;
		}
		
		return true;
	}
}