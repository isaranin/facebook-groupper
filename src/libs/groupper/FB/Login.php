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
 * created by Ivan Saranin <ivan@saranin.com>, on 22.03.2016, at 18:02:38
 */

namespace Groupper\FB;

/*
 * Module for Login class
 * 
 */

class Login {
	
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
	 * Facebook permissions
	 * 
	 * Main
	 * email
	 * user_hometown
	 * user_religion_politics
	 * publish_actions
	 * user_likes
	 * user_status
	 * user_about_me
	 * user_location
	 * user_tagged_places
	 * user_birthday
	 * user_photos
	 * user_videos
	 * user_education_history
	 * user_posts
	 * user_website
	 * user_friends
	 * user_relationship_details
	 * user_work_history
	 * user_games_activity
	 * user_relationships
	 * 
	 * Events, Groups & Pages
	 * ads_management
	 * pages_manage_leads
	 * rsvp_event
	 * ads_read
	 * pages_show_list
	 * user_events
	 * manage_pages
	 * publish_pages
	 * user_managed_groups
	 * pages_manage_cta
	 * read_page_mailboxes
	 * 
	 * Open Graph Actions
	 * user_actions.books
	 * user_actions.music
	 * user_actions.video
	 * user_actions.fitness
	 * user_actions.news
	 * 
	 * Other
	 * read_audience_network_insights
	 * read_custom_friendlists
	 * read_insights
	 * 
	 * @var array
	 */
	public $permissions = [];
	
	/**
	 * Text of last error;
	 * @var string
	 */
	public $lastError = '';
	
	/**
	 * Facebook object
	 * @var \Facebook\Facebook
	 */
	protected $fb = null;
	
	/**
	 * Constructor
	 * 
	 * @param string $appId application id
	 * @param string $appToken secret token
	 * @param string $graphVersion graph api version
	 * @param array $permissions facbook permissions
	 */
	public function __construct($appId, $appToken, $graphVersion, $permissions) {
		$this->appId = $appId;
		$this->appToken = $appToken;
		$this->graphVersion = $graphVersion;
		$this->permissions = $permissions;
		
		$this->fb = new \Facebook\Facebook([
			'app_id' => $this->appId,
			'app_secret' => $this->appToken,
			'default_graph_version' => $this->graphVersion
		]);		
	}
	
	/**
	 * Method return login url, for callback url
	 * 
	 * @param string $url url for callback
	 * @return string
	 */
	public function getLoginUrl($url) {
		$helper = $this->fb->getRedirectLoginHelper();
		$loginUrl = $helper->getLoginUrl($url, $this->permissions);
		return $loginUrl;
	}
	
	/**
	 * Method return access token from redirect login method
	 * 
	 * @return string|false access token return, otherwise false if error
	 */
	public function getAccessToken() {
		$res = false;
		$helper = $this->fb->getRedirectLoginHelper();
		try {
			$res = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			$this->lastError = sprintf('Graph returned an error: %s', $e->getMessage());
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			$this->lastError = sprintf('Facebook SDK returned an error: %s', $e->getMessage());
		}
		return $res;
	}
	
	/**
	 * Method check if we have needed permissions or not
	 * 
	 * @param string $accessToken facbook access token
	 * @return boolean
	 */
	public function checkPermissions($accessToken) {
		$connector = new Connector(
			$this->appId,
			$this->appToken,
			$this->graphVersion
		);
		
		$res = $connector->connect($accessToken);
		if ($res !== false) {
			$permissions = $connector->request('GET', '/me/permissions');
			if (isset($permissions['data'])) {
				$needPermissions = $this->permissions;
				foreach($permissions['data'] as $permission) {
					if ($permission['status'] === 'granted') {
						$index = array_search($permission['permission'], $needPermissions);
						if ($index > 0) {
							unset($needPermissions[$index]);
						}
					}
				}
				$res = count($needPermissions) === 0;
				
				if (!$res) {
					$this->lastError = sprintf('We need such permissions: %s', implode(', ', $needPermissions));
				}
			} else {
				$this->lastError = $connector->lastError;
				$res = false;
			}
		} else {
			$this->lastError = $connector->lastError;
		}
		return $res;
	}
}
