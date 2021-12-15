<?php
    function makeFacebookApiCall($endpoint, $params) {
		// open curl call, set endpoint and other curl params
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $endpoint . '?' . http_build_query($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		// get curl response, json decode it, and close curl
		$fbResponse = curl_exec($ch);
		$fbResponse = json_decode($fbResponse, TRUE);
		curl_close($ch);
		return array( // return response data
			'endpoint' => $endpoint,
			'params' => $params,
			'has_errors' => isset( $fbResponse['error'] ) ? TRUE : FALSE, // boolean for if an error occured
			'error_message' => isset( $fbResponse['error'] ) ? $fbResponse['error']['message'] : '', // error message
			'fb_response' => $fbResponse // actual response from the call
		);
	}

    function getFacebookLoginUrl() {
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

    function tryAndLoginWithFacebook($get) {
        include 'dbconfig.php';
		// assume fail
		$status = 'fail';
		$message = '';
        if(isset($get['error'])) {
            $message = $get['error_description'];
        } else {
            $accessTokenInfo =  getAccessTokenWithCode($get['code']);
            if($accessTokenInfo['has_errors']) {
                $message = $accessTokenInfo['error_message'];
            } else {
                $_SESSION['fb_access_token'] = $accessTokenInfo['fb_response']['access_token'];
                $fbUserInfo = getFacebookUserInfo( $_SESSION['fb_access_token']);
                $email = $fbUserInfo['fb_response']['email'];
                $fname = $fbUserInfo['fb_response']['first_name'];
                $lname = $fbUserInfo['fb_response']['last_name'];   
                //check if user is already signed up
                $sql = "SELECT * FROM users_profile WHERE email='$email'";
                $rs = $conn->query($sql);
                if(!mysqli_num_rows($rs) >= 1) {
                    $sql = "INSERT INTO users_profile SET email='$email', first_name='$fname', last_name='$lname'";
                    if(!$conn->query($sql)) {
                        echo $conn->error;//getting the error
                    }
                }
                //gets id of logged in user
                $_SESSION['userid'] = $email;
                $_SESSION['name'] = "$fname $lname";
                //indicates that a user is logged in
                $_SESSION['is_logged_in'] = true;
            }
        }
        return array(
            'status' => $status,
            'message' => $message,
        );
    }
?>