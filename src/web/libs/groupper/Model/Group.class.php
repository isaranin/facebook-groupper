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

/*
 * Module for Group class
 * 
 * <DESCRIPTION>
 */

class Group extends \MysqliDb\dbObject {
    protected $dbTable = "groups";
	protected $primaryKey = "id";
    protected $dbFields = Array (
        'url' => Array ('text', 'required'),
        'code' => Array ('text'),
        'fbid' => Array ('text'),
        'syncfeed' => Array ('bool')
    );

    protected $timestamps = Array ('createdAt', 'updatedAt');
    protected $relations = Array (
        'products' => Array ("hasMany", "product", 'userid')
    );
}
