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
 * created by Ivan Saranin <ivan@saranin.com>, on 11.03.2016, at 12:22:10
 */

/* 
 * Module sample
 * 
 * Sample private config file
 */

$res = (object)[
	//facebook config
	'facebook' => (object) [
		'id' => 'app-id',
		'secret' => 'app-secret',
		'version' => 'v2.5',
		//can take it here https://developers.facebook.com/tools/accesstoken/
		'accessToken' => 'user-access-token'
	],
	//database config
	'db' => (object) [
		'host' => 'localhost',
		'base' => 'some-db-name',
		'user' => 'some-user-name',
		'password' => 'some-password',
		'charset' => 'utf8'
	]
];

return $res;