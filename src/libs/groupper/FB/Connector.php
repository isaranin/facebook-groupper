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
 * created by Ivan Saranin <ivan@saranin.com>, on 15.03.2016, at 21:07:32
 */

namespace Groupper\FB;

/*
 * Module for Connector class
 * Class for amke connection to facebook api
 */

class Connector {
	/**
	 * App ID
	 * @var string
	 */
	public $appId = '';
	
	/**
	 * App secret token
	 * @var string
	 */
	public $appToken = '';
	
	/**
	 * Facebook graph version
	 * @var string
	 */
	public $graphVersion = '';
	
	/**
	 * Text of last error;
	 * @var string
	 */
	public $lastError = '';
	
	/**
	 * If we connected to facbook api or nor
	 * @var boolean;
	 */
	public $connected = false;
	
	/**
	 * Facebook object
	 * @var \Facebook\Facebook
	 */
	protected $fb = null;
	
	/**
	 * Access token
	 * @var string
	 */
	protected $accessToken = null;
	
	/**
	 * Mysql datetime format
	 * @var string
	 */
	static public $MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s'; //2016-03-15 24:00:00
	
	/**
	 * Facebook datetime format
	 * @var string
	 */
	static public $FB_DATETIME_FORMAT = 'Y-m-d?H:i:sT'; //2016-03-15T24:00:00+0000
	
	/**
	 * Constructor
	 * 
	 * @param string $appId application id
	 * @param string $appToken secret token
	 * @param string $graphVersion graph api version
	 */
	public function __construct($appId, $appToken, $graphVersion) {
		$this->appId = $appId;
		$this->appToken = $appToken;
		$this->graphVersion = $graphVersion;
		
		$this->fb = new \Facebook\Facebook([
			'app_id' => $this->appId,
			'app_secret' => $this->appToken,
			'default_graph_version' => $this->graphVersion
		]);		
	}
	
	/**
	 * Method connect to Facebook API
	 * 
	 * @param string $accessToken acces token to graph api
	 * @return string | boolean false if not connected, true if connect ok, string if new access key
	 */
	public function connect($accessToken) {
		$res = true;
		
		try {
		
			$oAuth2Client = $this->fb->getOAuth2Client();

			$tokenMetadata = $oAuth2Client->debugToken($accessToken);

			$tokenMetadata->validateAppId($this->appId);

			$tokenMetadata->validateExpiration();

			$accessToken = new \Facebook\Authentication\AccessToken(
					$accessToken, 
					$tokenMetadata->getExpiresAt()->getTimestamp()
			);
			
			$newAccessToken = $accessToken->getValue();
			
			if (!$accessToken->isLongLived()) {
				$newAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
				$res = $newAccessToken;
			}
			
			$this->accessToken = $newAccessToken;
			$this->connected = true;
			
		} catch (\Exception $ex) {
			$res = false;
			$this->lastError = $ex->getMessage();
		}
		
		return $res;
	}
	
	/**
	 * Method request data from Facebook Graph
	 * 
	 * @param string $method 'GET' or 'POST' or 'DELETE'
	 * @param string $command gaph api command
	 * @param array $params additional params
	 * @return boolean | array false if error, data if ok
	 */
	public function request($method, $command, $params = []) {
		if (empty($this->accessToken)) {
			$this->lastError = 'You should connect first, use $this->connect() method.';
			return false;
		}
		
		$res = true;
		try {
			$request = $this->fb->sendRequest($method, $command, $params, $this->accessToken);
			$res = $request->getDecodedBody();
		} catch (\Exception $ex) {
			$res = false;
			$this->lastError = $ex->getMessage();
		}
		
		return $res;
	}
	
	/**
	 * Method convert facebook date time to needed format
	 * @param string $time datetime string in facebook format
	 * @param string $outFormat needed format, if empty we will use Mysql format
	 * @return string
	 */
	public function convertFBDateTime($time, $outFormat = '') {
		if (empty($outFormat)) {
			$outFormat = self::$MYSQL_DATETIME_FORMAT;
		}
		$res = \DateTime::createFromFormat(self::$FB_DATETIME_FORMAT, $time);
		
		return $res->format($outFormat);
	}
}
