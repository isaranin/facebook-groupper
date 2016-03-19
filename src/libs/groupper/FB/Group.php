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
 * created by Ivan Saranin <ivan@saranin.com>, on 18.03.2016, at 17:34:32
 */

namespace Groupper\FB;

/*
 * Module for Group class
 * Class to work with facebook groups
 */

class Group extends Connector {
	
	/**
	 * Normal group url
	 * 
	 * @var string
	 */
	static public $GROUP_LINK = 'https://www.facebook.com/groups/%s/';
	
	/**
	 * Convert facebook attachemtns to image source array
	 * 
	 * @param array $attachments attachment data from facebook
	 * @return array
	 */
	protected function getAttachments($attachments) {
		$res = [];
		if (!isset($attachments['attachments'])) {
			return $res;
		}
		$attachments = $attachments['attachments'];
		if (isset($attachments['data'])) {
			foreach($attachments['data'] as $attachment) {
				if ($attachment['type'] === 'album' && isset($attachment['subattachments']['data'])) {
					foreach($attachment['subattachments']['data'] as $subattachment) {
						if ($subattachment['type'] === 'photo') {
							$res[] = $subattachment['media']['image']['src'];
						}
					}
				}
			}
		}
		return $res;
	}

	/**
	 * Method to convert facebook feed group data to our model
	 * 
	 * @param array $data data taken from facebook
	 * @return array
	 */
	protected function normalizeFbFeedAnswer($data) {
		$res = [];
		foreach($data as $post) {
			$id = explode('_', $post['id']);
			// if no message then this is wrong post, we dot need it
			if (!isset($post['message'])) {
				continue;
			}
			$post = [
				'groupid' => $id[0],
				'postid' => $id[1],
				'authorid' => $post['from']['id'],
				'message' => $post['message'],
				'type' => $post['type'],
				'link' => isset($post['link'])?$post['link']:'',
				'published' => $post['is_published'],
				'visible' => !$post['is_hidden'],
				'created' => $this->convertFBDateTime($post['created_time']),
				'updated' => $this->convertFBDateTime($post['updated_time']),
				'attachments' => $this->getAttachments($post)
			];
			$res[] = $post;
		}
		return $res;
	}

	/**
	 * Method get feed from facebook
	 * 
	 * @param string $groupId facebook group id
	 * @param \DateTime $fromDate post updated since this date, can be null
	 * @param integer $limit how many post to take
	 * @return array|false if error return false otherwise array of posts
	 */
	public function feed($groupId, \DateTime $fromDate = null, $limit = 100) {
		$res = false;
		$params = [
			'fields' => 'created_time,message,link,attachments,updated_time,from,type,is_published,is_hidden',
			'limit' => $limit,
			'include_hidden' => 'true'
		];
		if (!is_null($fromDate)) {
			$params['since'] = $fromDate->getTimestamp();
		}
		$command = sprintf('/%s/feed', $groupId);
		$res = $this->request('GET', $command, $params);
		if (isset($res['data'])) {
			$res = $this->normalizeFbFeedAnswer($res['data']);
		}
		
		return $res;
	}
	
	/**
	 * Method takes groupcode from url
	 * 
	 * @param string $url link to group
	 * @return string|false return false if cant take group code, otherwise group code
	 */
	public function getGroupCodeFromUrl($url) {
		$res = false;
		// our template
		// ^http[s]?:\/\/www\.facebook\.com\/groups\/([\w\d-_]*).*$
		// what we usually have in GROUP_LINK variable 'https://www.facebook.com/groups/%s/'
		// mask all dots and slashed
		$pattern = str_replace(['/','.'], ['\/','\.'], self::$GROUP_LINK);
		// replace https and %s to regexp 
		$pattern = str_replace(['https','%s\/'], ['^http[s]?','([\w\d-_]*).*$'], $pattern);
		// add preg slashes and modifiers
		$pattern = sprintf('/%s/im', $pattern);
		
		$matches = [];
		if (preg_match($pattern, $url, $matches)) {
			if (isset($matches[1])) {
				$res = $matches[1];
			}
		}
		return $res;
	}
	
	/**
	 * Method convert group info to our model
	 * 
	 * @param array $data data received from facebook
	 * @return array 
	 */
	protected function normalizeFbGroupAnswer($data) {
		// we can find group code in email
		$groupCode = explode('@', $data['email']);
		if (count($groupCode) > 1) {
			$groupCode = $groupCode[0];
		} else {
			$groupCode = '';
		}
		$res = [
			'id' => $data['id'],
			'url' => sprintf(self::$GROUP_LINK, $groupCode),
			'code' => $groupCode,
			'name' => $data['name'],
			'description' => $data['description'],
			'icon' => $data['icon'],
			'cover' => '',
			'privacy' => $data['privacy'],
			'email' => $data['email'],
			'ownerid' => null
		];
		if (isset($data['cover']['source'])) {
			$res['cover'] = $data['cover']['source'];
		}
		if (isset($data['owner']['id'])) {
			$res['ownerid'] = $data['owner']['id'];
		}
		return $res;
	}
	
	/**
	 * Method takes group info from its id
	 * 
	 * @param string $id group id
	 * @return array|false return false if error, otherwise array data
	 */
	public function getGroupFromID($id) {
		$res = false;
		$params = [
			'fields' => 'description,name,id,icon,cover,privacy,owner,email'
		];
		$command = sprintf('/%s', $id);
		$res = $this->request('GET', $command, $params);
		if (is_array($res)) {
			$res = $this->normalizeFbGroupAnswer($res);
		} 
		return $res;
	}
	
	/**
	 * Method takes group info from group code
	 * 
	 * @param string $code group code
	 * @return array|false return false if error, otherwise array data
	 */
	public function getGroupFromCode($code) {
		$res = false;
		$params = [
			'fields' => 'id',
			'type' => 'group',
			'q' => $code
		];
		$res = $this->request('GET', '/search', $params);
		if (isset($res['data'][0])) {
			$res = $this->getGroupFromID($res['data'][0]['id']);
		} else {
			$this->lastError = sprintf('No group found with code - %s', $code);
			$res = false;
		}
		return $res;
	}
	
	/**
	 * Method takes group info from url
	 * 
	 * @param string $url link to group
	 * @return array|false return false if error, otherwise array data
	 */
	public function getGroupFromUrl($url) {
		$groupCode = $this->getGroupCodeFromUrl($url);
		if (!is_string($groupCode)) {
			$this->lastError = sprintf('Can`t take code from "%s"', $url);
			return false;
		}
		
		return $this->getGroupFromCode($groupCode);
	}
}
