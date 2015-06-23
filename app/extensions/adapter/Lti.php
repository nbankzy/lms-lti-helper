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
interface Lti {

	/**
	 * Convert request data into variables
	 *
	 * @access public
	 * @param array $request LTI request data
	*/
	public function processReq (array $request = array());

	/**
	 * Lti consumer Key
	 *
	 * @access public
	*/
	public function consumerKey ();
	/**
	 * Lti consumer secret
	 *
	 * @access public
	*/
	public function consumerSecret ();

	/**
	 * Lti message type
	 *
	 * @access public
	*/
	public function messageType ();

	/**
	 * Prep response data for lti return request
	 *
	 * @access public
	 * @param array $data Redirection data
	*/
	public function returnResponse ($data);

	/**
	 * Data return url
	 *
	 * @access public
	*/
	public function returnUrl ();

	/**
	 * Url from which the request comes from
	 *
	 * @access public
	*/
	public function serviceUrl ();

	/**
	 * result sourcedid
	 *
	 * @access public
	*/
	public function sourcedid ();

	/**
	 * User data username
	 *
	 * @access public
	*/
	public function username ();

}
?>