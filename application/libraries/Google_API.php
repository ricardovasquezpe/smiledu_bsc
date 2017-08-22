<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Google_API {

	public function __construct($params) {
	
    	require APPPATH . "third_party/Google/autoload.php";
    	
		$this->client = new Google_Client();
		$this->client->setApplicationName($params['app_name']);

		if($params['tipo_auth'] == 'CONSTANTES') {
		    $this->client->setClientId(GOOGLE_CLIENT_ID);
		    $this->client->setClientSecret(GOOGLE_CLIENT_SECRET);
		    
		} else if($params['tipo_auth'] == 'JSON') {
		    $this->client->setAuthConfigFile(APPPATH . 'config/client_secret.json');
		}
		
		$this->client->setRedirectUri($params['redirec_uri']);
		if(isset($params['scopes']) && is_array($params['scopes'])) {
		    
		    foreach ($params['scopes'] as $scope) {
		        if($scope == 'calendar') {
		            $scope = Google_Service_Calendar::CALENDAR;
		        } else if($scope == 'calendar_readonly') {
		            $scope = Google_Service_Calendar::CALENDAR_READONLY;
		        }
		        $this->client->addScope($scope);
		    }
		}
		$this->calendar = new Google_Service_Calendar($this->client);
	}

	public function loginUrl() {
        return $this->client->createAuthUrl();
    }

	public function getAuthenticate() {
        return $this->client->authenticate();
    }

	public function getAccessToken() {
        return $this->client->getAccessToken();
    }

	public function setAccessToken() {
        return $this->client->setAccessToken();
    }

	public function revokeToken() {
        return $this->client->revokeToken();
    }

    public function client(){
    	return $this->client;
    }
	
    public function getUser(){
    	$google_ouath = new Google_Service_Oauth2($this->client);
 		return (object)$google_ouath->userinfo->get();
    }

    public function isAccessTokenExpired(){
        return $this->client->isAccessTokenExpired();
    }
}
?>