<?
/* 
 * Test module api-connect-test
 * 
 */

include '../bootstrap.php';
$fb = new Facebook\Facebook([
  'app_id' => $_CONFIG->private->facebook->id,
  'app_secret' => $_CONFIG->private->facebook->secret,
  'default_graph_version' => $_CONFIG->private->facebook->version,
  'default_access_token' => $_CONFIG->private->facebook->accessToken, // optional
]);

// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
//   $helper = $fb->getRedirectLoginHelper();
//   $helper = $fb->getJavaScriptHelper();
//   $helper = $fb->getCanvasHelper();
//   $helper = $fb->getPageTabHelper();

try {
  // Get the Facebook\GraphNodes\GraphUser object for the current user.
  // If you provided a 'default_access_token', the '{access-token}' is optional.
  $response = $fb->sendRequest('GET','/search', ['q' => 'Phuketbuysellrent', 'type' => 'group']);
  $response2 = $fb->sendRequest('GET','', ['id' => 'https://www.facebook.com/groups/Phuketbuysellrent/']);
  //$response->getBody();

} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
var_dump($response->getDecodedBody());
var_dump($response2->getDecodedBody());
  //var_dump($response);