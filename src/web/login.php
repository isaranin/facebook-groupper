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
 * created by Ivan Saranin <ivan@saranin.com>, on 22.03.2016, at 17:39:41
 */

/* 
 * Module login
 * 
 * Login page
 */

include '../bootstrap.php';
include '../db.php';

// init fb login
$login = new \Groupper\FB\Login(
	$_CONFIG->private->facebook->id,
	$_CONFIG->private->facebook->secret,
	$_CONFIG->private->facebook->version,
	$_CONFIG->private->facebook->permissions
);

// find last facebook token
$lastDBToken = \Groupper\Model\AccessToken::ObjectBuilder()->orderBy('time')->getOne();

if (!is_null($lastDBToken)) {
	$lastToken = $lastDBToken->token;
} else {
	$lastToken = $_CONFIG->private->facebook->accessToken;
}

$layoutData = [
	'connected' => false
];

// check permissions
if (!$login->checkPermissions($lastToken)) {
	$currentUrl = sprintf('%s://%s%s', $_SERVER['REQUEST_SCHEME'], $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
	$callbackUrl = sprintf('%s/%s',dirname($currentUrl),'fb-callback.php');

	$fbUrl = $login->getLoginUrl($callbackUrl);
	$layoutData['callbackUrl'] = $callbackUrl;
	$layoutData['fbUrl'] = $fbUrl;
	$layoutData['error'] = $login->lastError;
	$tplName = 'login-form';
} else {
	// init fb connector to take me information
	$connector = new \Groupper\FB\Connector(
		$_CONFIG->private->facebook->id,
		$_CONFIG->private->facebook->secret,
		$_CONFIG->private->facebook->version
	);
	$connector->connect($lastToken);
	// take information about user
	$me = $connector->request('GET','/me');
	if (isset($me['name'])) {
		$layoutData['connected'] = true;
		$layoutData['user']['name'] = $me['name'];
		$layoutData['user']['id'] = $me['id'];

		$tplName = 'login-success';
	} else {
		$layoutData['error'] = $connector->lastError;

		$tplName = 'login-form';
	}
}
// init render page
$render = new \Groupper\Render\Page();
$render->init(__dir__.'/tpl');

// render page
$page = $render->process($tplName, $layoutData);

echo $page;