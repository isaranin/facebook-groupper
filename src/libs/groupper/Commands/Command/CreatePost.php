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
 * created by Ivan Saranin <ivan@saranin.com>, on 23.03.2016, at 22:49:08
 */

namespace Groupper\Commands\Command;

/*
 * Module for CreatePost class
 */

class CreatePost extends AbstractFbCommand{
	
	protected $command = 'createpost';
	
	/**
	 * Parametrs should be array with next structure
	 * $params['post_id'] - post id in database table posts
	 * AND (
	 *		$params['collection_id'] - collection id from table collections
	 *		OR
	 *		$params['group_id'] - group id from table groups
	 * )
	 * @param array $params
	 */
	protected function checkParams($params) {
		$res = '';
		$params = (array)$params;
		if (!isset($params['post_id'])) {
			$res .= (!empty($res)?', ':'').'"post_id" should exist';
		}
		if (!isset($params['collection_id']) && !isset($params['group_id'])) {
			$res .= (!empty($res)?', ':'').'"collection_id" or "group_id" should exist';
		}
		if (empty($res)) {
			$res = true;
		}
		return $res;
	}
	
	/**
	 * Method returns groups by its id, or collection id
	 * 
	 * @param string $id group id or collection id
	 * @param boolean $isCollectionID true if we pass collection id
	 * @return array array of group
	 */
	private function getGroups($id, $isCollectionID) {
		if ($isCollectionID) {
			$dbGroups = $this->db->where('collection_id', $id)->get('groups_to_collections');
		} else {
			//$dbGroups = $this->db->where('id', $id)->get('groups');
			// we dont care about, exist group id in db or not
			$dbGroups = [['id' => $id]];
		}
		if (!is_array($dbGroups)) {
			return $this->db->getLastError();
		}
		if (count($dbGroups) == 0) {
			if ($isCollectionID) {
				$res = sprintf('Can`t find collection with id: %s', $id);
			} else {
				$res = sprintf('Can`t find group with id: %s', $id);
			}
			return $res;
		}
		
		$groups = [];
		foreach ($dbGroups as $dbGroup) {
			if ($isCollectionID) {
				$groups[] = $dbGroup['group_id'];
			} else {
				$groups[] = $dbGroup['id'];
			}
		}
		
		return $groups;
	}
	
	/**
	 * Method return post by its ID
	 * 
	 * @param string $id
	 * @return string|\Groupper\Model\Post return error text or post object
	 */
	private function getPostDataById($id) {
		$post = \Groupper\Model\Post::byId($id);

		if (is_null($post)) {
			return sprintf('Can`t find post with id: ', $id);
		}
		return $post;
	}
	
	private function propertiesExist($obj, $needed) {
		$paramKeys = array_keys((array)$obj);
		$diff = array_diff($needed, $paramKeys);
		if (count($diff) > 0) {
			return 
				sprintf('Wrong data format, keys waiting: %s, $keys get: %s', 
						implode(',', $needed),  
						implode(',', $paramKeys)
				);
		}
		return true;
	}
	
	/**
	 * Method convert post to facebook format
	 * Retun array which facebook method to execute and params for this method
	 * ['method' => ..., 'params' => ...]
	 * 
	 * @param \Groupper\Model\Post $data
	 * @return string|array return string with error text or ['method' => ..., 'params' => ...]
	 */
	private function convertPostToFBFormat($data) {
		$res = [];
		switch (strtolower($data->type)) {
			case 'photo':
				$error = $this->propertiesExist($data->params, ['file','url']);
				if (is_string($error)) {
					return $error;
				}
				$res['method'] = 'addPhoto';
				$res['params'] = [
					$data->message,
					$data->params->file,
					$data->params->url
				];
				break;
			case 'post':				
				$res['method'] = 'addPost';
				$res['params'] = [
					$data->message
				];
				break;
			case 'link':
				$error = $this->propertiesExist($data->params, 
												['link','caption','description','picture','name']);
				if (is_string($error)) {
					return $error;
				}
				$res['method'] = 'addLink';
				$res['params'] = [
					$data->message,
					$data->params->link,
					$data->params->caption,
					$data->params->description,
					$data->params->picture,
					$data->params->name,
				];
				break;
			default:
				$res = sprintf('Wrong post type: %s', $data->type);
				break;
		}
		return $res;
	}
	
	/**
	 * Send post to group
	 */
	protected function innerExecute($params) {
		$params = (array) $params;
		$res = parent::innerExecute($params);
		if (is_string($res)) {
			return $res;
		}
		$groups = $this->getGroups(
				isset($params['collection_id'])?$params['collection_id']:$params['group_id'], 
				isset($params['collection_id'])
		);
		if (is_string($groups)) {
			return $groups;
		}
		$post = $this->getPostDataById($params['post_id']);
		if (is_string($post)) {
			return $post;
		}
		
		$post = $this->convertPostToFBFormat($post);
		if (is_string($post)) {
			return $post;
		}
		
		foreach($groups as $groupID) {
			$res = call_user_func_array([$this->fb, $post['method']], array_merge([$groupID], $post['params']));
		}
		
		if ($res === false) {
			return $this->fb->lastError;
		}
		
		return true;
	}
}
