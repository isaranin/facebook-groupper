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
 * created by Ivan Saranin <ivan@saranin.com>, on 12.03.2016, at 15:05:58
 */

namespace Groupper\Model;

/**
 * Module for Group class
 * 
 */

/**
 * Groups tables in db
 * 
 */
class Group extends \MysqliDb\dbObject {
	
    protected $dbTable = 'groups';
	
	protected $primaryKey = 'id';
	
    protected $dbFields = [
        'url' => ['text', 'required'],
        'code' => ['text'],
        'fbid' => ['text'],
        'syncfeed' => ['bool'],
		'lastsync' => ['datetime'],
		'created' => ['datetime'],
        'updated' => ['datetime']
    ];
	
	protected $updateField = 'updated';
	protected $createField = 'created';
	
}
