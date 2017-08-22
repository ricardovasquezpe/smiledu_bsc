<?php
        if (!defined('BASEPATH')) exit('No direct script access allowed');
        set_include_path(APPPATH . 'third_party/' . PATH_SEPARATOR . get_include_path());
        require_once APPPATH . 'third_party/Google/autoload.php';

        class Google {
            function getClient(){
                $client = new Google_Client();
                $client->setClientId(CLIENT_ID);
                $client->setClientSecret(CLIENT_SECRET);
                $client->setRedirectUri(REDIRECT_URI);
                $client->addScope(SCOPES);
                $client->setAccessType('offline');
                $client->setApprovalPrompt('force');

                if(file_exists(__DIR__ . '/accessToken.json')){
                    $accessToken = file_get_contents(__DIR__ . '\accessToken.json');
                    $client->setAccessToken($accessToken);
                }
                
                if($client->isAccessTokenExpired()){
                    if(file_exists(__DIR__ . '/accessToken.json')){
                        unlink(__DIR__ . '/accessToken.json');
                    }
                    
                    if(!isset($_GET['code'])){
                        $authUrl = $client->createAuthUrl();
                        redirect($authUrl,'refresh');
                    }else{
                        $client->authenticate($_GET['code']);
                        $code = $client->getRefreshToken();
                        $client->refreshToken($code);
                        
                        $acc  = $client->getAccessToken();
                        file_put_contents(__DIR__ . '/accessToken.json', $acc);
                    }
                }
               
                return $client;
            } 
            
            function insertFile($client, $service, $mimeType, $ruta, $nombreArchivo){
               //http://www.sitepoint.com/web-foundations/mime-types-complete-list/
	           $resp = false;
                if ($client->getAccessToken()) {
                    $file = new Google_Service_Drive_DriveFile();
                    $file->setTitle($nombreArchivo);
                    $result2 = $service->files->insert(
                        $file,
                        array(
                            'data' => file_get_contents($ruta),
                            'mimeType' => $mimeType,
                            'uploadType' => 'multipart'
                        )
                    );
                    return true;
                }
            }
        } 
        ?>