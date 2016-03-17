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
 * created by Ivan Saranin <ivan@saranin.com>, on 12.03.2016, at 15:06:59
 */

/* 
 * Module autoload
 */
spl_autoload_register(function ($className) {
	$namespaces = explode('\\', $className);

	if ((count($namespaces) === 0) || (strtolower($namespaces[0]) !== 'groupper')) {
		return false;
	}

	// убираем название sherlox
	array_shift($namespaces);

	// все файлы храняться в папке lib
	$className = implode('/', $namespaces);
	// если нужно подключаем файл Exceptions, с исключениями характерными для выбранного неймспейса
	if ( (stristr($className, "exception") !== false) ) {
		$path = substr($className,0,strrpos($className,'/'));
		$path = __DIR__ . '/' . $path . '/Exceptions.class.php';
		if ( file_exists($path) ) {
			require_once($path);
			return true;
		}
	}

	$path = __DIR__.'/'.$className.'.class.php';
	if (file_exists($path)) {
		require_once($path);
		return true;
	}

	$path = __DIR__.'/'.$className.'.php';
	if (file_exists($path)) {
		require_once($path);
		return true;
	}

	return false;
});


