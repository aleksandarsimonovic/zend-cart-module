<?php

/**
 * Make a redirect using Zend_Controller_Action_Helper_Redirector
 *
 * @author Aleksandar Simonovic
 */
class Application_Model_Redirector
{
	/**
	 * @var Zend_Controller_Action_Helper_Redirector
	 */
	protected $_redirector = null;
	
	public function __construct(Zend_Controller_Action_Helper_Redirector $redirector)
	{
		$this->_redirector = $redirector;
	}
	
	/**
	 * Make a redirect 303
	 * 
	 * @param string $controller
	 * @param string $action
	 */
	public function redirect($controller, $action)
	{
		$this->_redirector->setCode(303)
						  ->setExit(false)
						  ->setGotoSimple($controller, $action);

		$this->_redirector->redirectAndExit();
	}
}
