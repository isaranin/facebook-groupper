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
 * created by Ivan Saranin <ivan@saranin.com>, on 12.03.2016, at 17:01:51
 */

namespace Groupper\Model;

/*
 * Module for FeedItem class
 * 
 */

class FeedItem extends \MysqliDb\dbObjectMultiKey {
	
	protected $dbTable = 'feed';
	
	protected $primaryKey = ['groupid', 'postid'];
	
	protected $dbFields = [
		'authorid' => ['text', 'required'],
		'message' => ['text', 'required'],
		'type' => ['int', 'required'],
		'link' => ['int', 'required'],
		'updated' => ['datetime', 'required'],
		'created' => ['datetime', 'required'],
		'attachments' => ['text', 'required'],
	];
	
	protected $arrayFields = ['attachments'];
}
