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
 * created by Ivan Saranin <ivan@saranin.com>, on 28.03.2016, at 12:29:59
 */

namespace SA\Log;

/*
 * Module for File class
 */

class File {
	/**
	 * Time template
	 * 
	 * @var string
	 */
	private $timeFormat = 'H:i:s:u';

	/**
	 * Date template
	 * 
	 * @var string
	 */
	private $dateFormat = 'd.m.Y';

	/**
	 * Filename, you can use time format variables
	 * @see http://php.net/manual/en/function.strftime.php
	 *
	 * @var string
	 */
	private $fileName;
	
	/**
	 * Max size for log file
	 * 
	 * @var int
	 */
	private $maxSize = -1;

	/**
	 * Divider
	 * 
	 * @var string
	 */
	protected $delimiter = "\t";

	/**
	 * Open log filename
	 *
	 * @param string $fileName file to open
	 * @return false|descriptor open file desctipor or false if error
	 */
	protected function openFile($fileName) {
		$dirName = dirname($fileName);
		// check if folder exist, if not create it
		if (!file_exists($dirName)) {
			mkdir($dirName, 0777, true);
		}
		$openMode = 'a';

		// clear cach
		clearstatcache();

		// if file more bigger then we need then replace file
		if (file_exists($fileName) &&
			($this->maxSize > 0) &&
			(filesize($fileName) >$this->maxSize)) {
			$openMode = 'w';
		}

		// open file
		return fopen($fileName, $openMode);
	}

	/**
	 * Method for conevrtigns args to strings
	 *
	 * @param array $args arguments
	 * @return string
	 */
	protected function convertArgs($args) {
		$res = array();
		foreach($args as $argument) {
			switch (gettype($argument)) {
				case 'array':
					$str = array();
					foreach($argument as $key=>$value) {
						if (is_array($value)) {
							$str[] = $key.'="'.  json_encode($value).'"';
						} else {
							$str[] = $key.'='.strval($value);
						}
					}
					$res[] = implode(',', $str);
					break;
				case 'object':
					$res[] = json_encode($argument);
					break;
				default:
					$res[] = strval($argument);
			}
		}
		return $res;
	}
	/**
	 * Write string to log file
	 *
	 * @param ... any count of paramaters, they all whil be added in log using divider
	 * @return boolean 
	 */
	public function put() {
		if (empty($this->fileName)) {
			return false;
		}

		if (func_num_args() == 0) {
			return false;
		}
		$curDateTime = new \DateTime();
		$curTime = time();
		$fileName = strftime($this->fileName, $curTime);
		// open file
		$fp = $this->openFile($fileName);
		if ($fp === false) {
			trigger_error('Can`t open file - '.$fileName, E_USER_WARNING);
			return false;
		}
		// convert args
		$putArray = $this->convertArgs(func_get_args());
		// add date and time
		array_unshift(
			$putArray,
			$curDateTime->format($this->timeFormat),
			$curDateTime->format($this->dateFormat)
		);
		$putStr = implode($this->delimiter, $putArray);
		
		$res = fwrite($fp, $putStr.PHP_EOL);
		fclose($fp);
		if (!$res) {
			trigger_error('Some proble with writing to log file - '.$this->fileName, E_USER_WARNING);
			return false;
		}
		return true;
	}
	
	/**
	 * Add string to log file using sprintf as fitrst parameter
	 * @param @str sprintf template
	 * @param ... @args arguments for template
	 * @return string
	 */
	public function pput() {
		$args = func_get_args();
		$str = array_shift($args);
		return $this->put(vsprintf($str, $args));
	}
	
	/**
	 * Constructor
	 *
	 * @param string $fileName log filename 
	 * @see http://php.net/manual/en/function.strftime.php
	 * @param integer $maxSize max filesize, -1 means dont use
	 * @param string $delimiter divider
	 */
	public function __construct($fileName, $maxSize = -1, $delimiter="\t") {
		$this->fileName = $fileName;
		$this->maxSize = $maxSize;
		$this->delimiter = $delimiter;
	}
}

