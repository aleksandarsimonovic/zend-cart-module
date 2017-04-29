<?php


require_once 'BaseController.php';

class IndexController extends BaseController
{
	/**
	 * @var Zend_Controller_Action_Helper_Redirector
	 */
	protected $_redirector = null;
	
	protected $dbAdapter;
	
	protected $productsGetter;

	public function init()
	{
		$this->dbAdapter 	  = $this->getInvokeArg('bootstrap')->getPluginResource('db')->getDbAdapter();
		$this->productsGetter = new Application_Model_ProductsGetter($this->dbAdapter);
	}

	/**
	 * Product list
	 */
    public function indexAction()
    {
    	Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');

    	$paginator = Zend_Paginator::factory( $this->productsGetter->getProducts() );
    	$paginator->setCurrentPageNumber( $this->_getParam('page', 1) );
        $paginator->setItemCountPerPage(3);
    	$this->view->paginator = $paginator;
    }

    /**
     * Product details
     */
    public function detailsAction()
    {
    	$params = $this->getRequest()->getParams();
    	if (isset($params['id'])) {

    		$productRecord = $this->productsGetter->getProducts($params['id']);
    		
    		if ($productRecord) {
    			$this->view->product = $productRecord[0];
    		}
    		
    	}
    }
}
