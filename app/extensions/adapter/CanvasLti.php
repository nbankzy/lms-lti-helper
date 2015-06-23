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
class CanvasLti implements Lti {

	// protected $request = array();
	/**
	 * Convert request data into variables
	 *
	 * @param array $request lti request data
	 * @access public
	*/
	public function processReq (array $request = array()) {
		$this->request = $request;
		$this->consumerKey = $this->_setValue('oauth_consumer_key');
		$this->intendedUse = $this->_setValue('ext_content_intended_use');
		$this->messageType = $this->_setValue('lti_message_type');
		$this->returnUrl = $this->_setValue('ext_content_return_url');
		$this->roles = $this->_setValue('roles');
		$this->serviceUrl = $this->_setValue('lis_outcome_service_url');
		$this->sourcedid = $this->_setValue('lis_result_sourcedid');
		$this->username = $this->_setValue('custom_canvas_user_login_id');
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
		return array(
			"method_type" => "get",
			"return_url" => $this->returnUrl,
			"data" => $data
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
	}

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