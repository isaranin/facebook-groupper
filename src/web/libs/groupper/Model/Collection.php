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
 * created by Ivan Saranin <ivan@saranin.com>, on 13.03.2016, at 23:52:49
 */

namespace Groupper\Model;

/*
 * Module for Collection class
 * 
 */

class Collection extends \MysqliDb\dbObject {
	
	protected $dbTable = 'collections';
	
	protected $primaryKey = 'id';
	
	protected $dbFields = [
		'name' => ['text', 'required'],
		'description' => ['text']
	];
//	protected $relations = [
//        //'groups' => ['hasManyToMany', 'group', 'group_to_collections', 'collectionid', 'groupid']
//		'groups' => ['hasOne', '\Groupper\Model\Group', 'group_id']
//    ];
}
