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
 * created by Ivan Saranin <ivan@saranin.com>, on 23.03.2016, at 22:28:51
 */

namespace Groupper\Commands\Command;

/*
 * Module for Test class
 */

class Test extends AbstractFbCommand {
	
	protected $command = 'test';
	
	protected function checkParams($params) {
		return true;
	}
	
	protected function innerExecute($params) {
		return true;
	}
}
