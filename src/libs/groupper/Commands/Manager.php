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
 * created by Ivan Saranin <ivan@saranin.com>, on 23.03.2016, at 20:11:22
 */

namespace Groupper\Commands;

/*
 * Module for Manager class
 */

class Manager {
	
	/**
	 * Sub namespace where we have all commands
	 * 
	 * @var string
	 */
	private $commandSubNamespace = 'Command';
	
	/**
	 * Parent class for all commands
	 * @var string
	 */
	private $parentCommandClass = 'AbstractCommand';
	
	/**
	 * Array of command to classname pairs
	 * @var array
	 */
	protected $commands = [];
	
	/**
	 * Last error text
	 * @var string
	 */
	public $lastError = '';
	
	/**
	 * Method fill commands array with command classname pairs, founded in subir
	 * 
	 * @return type
	 */
	private function fillCommands() {
		if (!empty($this->commands)) {
			return;
		}
		$folder = sprintf('%s/%s/', __DIR__, $this->commandSubNamespace);
		$abstractClassName = sprintf('\%s\%s\%s', __NAMESPACE__, $this->commandSubNamespace, $this->parentCommandClass);
		foreach (glob($folder.'*.php') as $filename) {
			$className = sprintf('\%s\%s\%s', __NAMESPACE__, $this->commandSubNamespace, pathinfo($filename, PATHINFO_FILENAME));
			$classRef = new \ReflectionClass($className);

			if (!$classRef->isAbstract() && $classRef->isSubclassOf($abstractClassName)) {
				
				$classDefProp = $classRef->getDefaultProperties();
				$this->commands[$classDefProp['command']] = $className;
			}
		}
	}
	
	/**
	 * Method return insatnce of command class by its text command
	 * 
	 * @param string $name command name
	 * @return \Groupper\Commands\AbstractCommand|false return false if error
	 */
	public function getCommandByName($name) {
		$this->fillCommands();
		$res = false;
		$name = strtolower($name);
		if (isset($this->commands[$name])) {
			$class = $this->commands[$name];
			$res = new $class();
		} else {
			$this->lastError = sprintf('Command "%s" was not found', $name);
		}
		return $res;
	}
}
