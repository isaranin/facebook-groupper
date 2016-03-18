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
 * created by Ivan Saranin <ivan@saranin.com>, on 18.03.2016, at 16:37:33
 */

namespace Groupper\Model;

/*
 * Module for AccessToken class
 * 
 */

class AccessToken extends \MysqliDb\dbObject {
	protected $dbTable = 'fb_access_tokens';
	
	protected $primaryKey = 'id';
	
	protected $dbFields = [
		'time' => ['datetime', 'required'],
		'token' => ['text', 'required']
	];
}
