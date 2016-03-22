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
 * created by Ivan Saranin <ivan@saranin.com>, on 22.03.2016, at 19:16:28
 */

namespace Groupper\Render;

/*
 * Module for Page class
 */

class Page {
	/**
	 * Template folder
	 * @var string
	 */
	private $tplFolder = '';
	
	/**
	 * Method for init class, should be executed before use
	 * 
	 * @param string $tplFolder template folder
	 */
	public function init($tplFolder) {
		$this->tplFolder = $tplFolder;
	}
	
	/**
	 * Method render data to template
	 * 
	 * @param string $pageName page template name
	 * @param string $data page data
	 * @return string
	 */
	public function process($pageName, $data) {
		$filename = sprintf('%s/%s.tpl.php', $this->tplFolder, $pageName);
		if (!file_exists($filename)) {
			return false;
		}
		
		ob_start();
		$tpl = $data;
		include $filename;
		$res = ob_get_contents();
		ob_end_clean();
		return $res;
	}
}
