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
 * created by Ivan Saranin <ivan@saranin.com>, on 23.03.2016, at 23:42:00
 */

namespace Groupper\Model;

/*
 * Module for Post class
 */

class Post extends \MysqliDb\dbObject {
	
	protected $dbTable = 'posts';
	
	protected $primaryKey = 'id';
	
    protected $dbFields = [
        'message' => ['text'],
		'type' => ['text', 'required'],
		'data' => ['text'],
		'created' => ['datetime'],
        'updated' => ['datetime']
    ];
	
	protected $updateField = 'updated';
	protected $createField = 'created';
	
	protected $jsonFields = ['data'];
}
