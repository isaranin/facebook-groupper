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
 * created by Ivan Saranin <ivan@saranin.com>, on 22.03.2016, at 19:01:08
 */

/* 
 * Module db
 */

// initialize database object
$db = new MysqliDb\MysqliDb(
	$_CONFIG->private->db->host,
	$_CONFIG->private->db->user,
	$_CONFIG->private->db->password,
	$_CONFIG->private->db->base,
	null,
	$_CONFIG->private->db->charset
);