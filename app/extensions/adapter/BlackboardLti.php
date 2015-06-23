<?php
namespace app\extensions\adapter;


/**
 *
 * Lti interface
 *
 * - processReq()
 * - consumerKey()
 * - consumerSecret()
 * - messageType()
 * - returnUrl()
 * - serviceUrl()
 * - sourcedid()
 * - username()
 */
class BlackboardLti implements Lti {

	/**
	 * Convert request data into variables
	 *
	 * @param array $request lti request data
	 * @access public
	*/
	public function processReq (array $request = array()) {
		$this->request = $request;
		$this->consumerKey = $this->_setValue('oauth_consumer_key');
		$this->intendedUse = $this->_setValue('lti_message_type');
		$this->messageType = $this->_setValue('lti_message_type');
		$this->returnUrl = $this->_setValue('content_item_return_url');
		$this->roles = $this->_setValue('roles');
		$this->serviceUrl = $this->_setValue('lis_outcome_service_url');
		$this->sourcedid = $this->_setValue('lis_result_sourcedid');
		$this->username = $this->_setValue('user_id');
	}

	/**
	 * Lti consumer Key
	 *
	 * @access public
	 * @return string LTI Consumer key
	*/
	public function consumerKey () {
		return $this->consumerKey;
	}
	/**
	 * Lti consumer secret
	 *
	 * @access public
	 * @return string LTI Consumer secret
	*/
	public function consumerSecret () {
		return "secret";
	}

	/**
	 * Lti message return type
	 *
	 * @access public
	 * @return string Request type
	*/
	public function intendedUse () {
		return $this->intendedUse;
	}

	/**
	 * Lti message type
	 *
	 * @access public
	 * @return string Request type
	*/
	public function messageType () {
		return $this->messageType;
	}

	/**
	 * Prep response data for return
	 *
	 * @access public
	 * @param array $data Array of relative return data [title, description, url, height, width]
	 * @return array Redirection data
	*/
	public function returnResponse ($data = array()) {

		$time = new \DateTime();
		$content = array(
			"@context" => "http://purl.imsglobal.org/ctx/lti/v1/ContentItem",
			"@graph" => array(
				array(
					"mediaType" => "application/x-shockwave-flash",
					"@type" => "ContentItem",
		            "placementAdvice" => array(
		                "displayWidth" => $data['width'],
		                "displayHeight" => $data['height'],
		                "documentTarget" => "iframe",
		                "windowTarget" => "_blank"
		            ),
		            "url" => $data['url'],
		            "title" => $data['title'],
		            "text" => $data['description']
				)
			)
		);

		$launch_data = array(
			"lti_message_type" => "ContentItemSelection",
			"lti_version" => "LTI-1p0",
			"data" => "",
			"oauth_nonce" => uniqid('', true),
			"oauth_version" => "1.0",
			"oauth_consumer_key" => $this->consumerKey,
			"oauth_timestamp" => $time->getTimestamp(),
			"content_items" => json_encode($content),
			"oauth_callback" => "about:blank",
			"oauth_signature_method" => "HMAC-SHA1"
		);
		
		$return_url = $this->returnUrl;
		// strip query params from url
		$url = preg_replace('/\?.*/', '', $return_url);

		$url_data = parse_url($return_url);
		// Retrieve url data
		parse_str($url_data['query'], $url_query);
		// Merge query data with passback data for signature
		$launch_data += $url_query;

		// sort keys in alphabetical order
		$launch_data_keys = array_keys($launch_data);
		sort($launch_data_keys);

		$launch_params = array();
		foreach ($launch_data_keys as $key) {
		  array_push($launch_params, $key . "=" . rawurlencode($launch_data[$key]));
		}

		$base_string = "POST&" . rawurlencode($url) . "&" . rawurlencode(implode("&", $launch_params));
		$secret = urlencode($this->consumerSecret()) . "&";
		$launch_data['oauth_signature'] = base64_encode(hash_hmac("sha1", $base_string, $secret, true));

		return array(
			"method_type" => "post",
			"return_url" => $url,
			"data" => $launch_data
		);
	}

	/**
	 * Data return url
	 *
	 * @access public
	 * @return string LTI return url
	*/
	public function returnUrl () {
		return $this->returnUrl;
	}

	/**
	 * User roles
	 *
	 * @access public
	 * @return string Users permission roles
	*/
	public function roles () {
		return $this->roles;
	}

	/**
	 * Url from which the request comes from
	 *
	 * @access public
	 * @return string
	*/
	public function serviceUrl () {
		return $this->serviceUrl;
	}

	/**
	 * result sourcedid
	 *
	 * @access public
	 * @return string
	*/
	public function sourcedid () {
		return $this->sourcedid;
	}

	/**
	 * User data username
	 *
	 * @access public
	 * @return string user_id
	*/
	public function username () {
		return $this->username;

	/**
	* Return value if it exists or empty string
	* @access protected
	* @param string $index Array key to search in request
	* @return string Request value or blank string
	*/
	protected function _setValue($index) {
		if(array_key_exists($index, $this->request)){
			return $this->request[$index];
		} else {
			return "";
		}
	}

}
?>