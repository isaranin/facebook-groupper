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
	 * Method for init object
	 */
	abstract public function init();
	
	/**
	 * Method for execute command
	 */
	abstract public function execute($params);
}