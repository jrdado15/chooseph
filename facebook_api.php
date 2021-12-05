<?php

    function makeFacebookApiCall( $endpoint, $params ) {
		// open curl call, set endpoint and other curl params
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $endpoint . '?' . http_build_query( $params ) );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );

		// get curl response, json decode it, and close curl
		$fbResponse = curl_exec( $ch );
		$fbResponse = json_decode( $fbResponse, TRUE );
		curl_close( $ch );

		return array( // return response data
			'endpoint' => $endpoint,
			'params' => $params,
			'has_errors' => isset( $fbResponse['error'] ) ? TRUE : FALSE, // boolean for if an error occured
			'error_message' => isset( $fbResponse['error'] ) ? $fbResponse['error']['message'] : '', // error message
			'fb_response' => $fbResponse // actual response from the call
		);
	}
    
    
    function getFacebookLoginUrl(){
        $endpoint = 'https://www.facebook.com/'.FB_GRAPH_VERSION.'/dialog/oauth';
        
        $params = array(
            'client_id' => FB_APP_ID,
            'redirect_uri' => FB_REDIRECT_URI,
            'state' => FB_APP_STATE,
            'scope' => 'email',
            'auth_type' => 'rerequest'
        );
    
        return $endpoint .'?'. http_build_query($params);
    }

    function getAccessTokenWithCode($code){
        $endpoint = 'https://graph.facebook.com/'.FB_GRAPH_VERSION. '/oauth/access_token';

        $params = array(
            'client_id' => FB_APP_ID,
            'client_secret' => FB_APP_SECRET,
            'redirect_uri' => FB_REDIRECT_URI,
            'code' => $code
        );

        return makeFacebookApiCall($endpoint, $params);
    }

    function getFacebookUserInfo($accessToken){
        $endpoint = FB_GRAPH_DOMAIN . 'me';

		$params = array( // params for the endpoint
			'fields' => 'first_name,last_name,email,picture',
			'access_token' => $accessToken
		);
		// make the api call
		return makeFacebookApiCall( $endpoint, $params );
    }


    function tryAndLoginWithFacebook( $get ) {
		// assume fail
		$status = 'fail';
		$message = '';

        if(isset($get['error'])){
            $message = $get['error_description'];
        }
        else{
            $accessTokenInfo =  getAccessTokenWithCode($get['code']);

            if($accessTokenInfo['has_errors']){
                $message = $accessTokenInfo['error_message'];
            }
            else{
                $_SESSION['fb_access_token'] = $accessTokenInfo['fb_response']['access_token'];
           
                $fbUserInfo = getFacebookUserInfo( $_SESSION['fb_access_token']);
            
                echo '<pre>';
                print_r($fbUserInfo);
                die();
            }
        }

        return array(
            'status' => $status,
            'message' => $message,
        );

    }

?>