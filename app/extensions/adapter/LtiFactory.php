<?php
namespace app\extensions\adapter;

use app\extensions\adapter\CanvasLti;

/**
 * This Class will act as a routing system for lms communications, will redirect
 * all calls to the specified class depending on what's defined in the request data
 *
 */
class LtiFactory
{

    /**
     * Class constructor
     *
     * @access public
     */
    public function __construct(array $request = array()) {
        switch ($request['tool_consumer_info_product_family_code']) {
            case "canvas":
                $this->_lms = new CanvasLti;
                $this->_lms->processReq($request);
                break;
            case "learn":
                $this->_lms = new BlackboardLti;
                $this->_lms->processReq($request);
                break;
            default:
                throw new \Exception("LMS not found");
        }
    }

    /**
     * Route calls to loaded class
     *
     * @access public
     * @param string $name name of method (eg addFile, copyFile)
     * @param array $arguments list of params
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->_lms, $name), $arguments);
    }
}
