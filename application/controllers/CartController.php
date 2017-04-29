<?php

/**
 * Cart Controller
 */

require_once 'BaseController.php';

class CartController extends BaseController
{
    protected $_redirector = null;

    protected $cacheHandler = null;
    
    protected $dbAdapter;
    
    protected $productsGetter;

    public function init()
    {
    	$this->_redirector = new Application_Model_Redirector( $this->_helper->getHelper('Redirector') );
    	
    	$this->cacheHandler = new Application_Model_CacheHandler();
    	
    	$this->dbAdapter 	= $this->getInvokeArg('bootstrap')->getPluginResource('db')->getDbAdapter();
    	
    	$this->productsGetter = new Application_Model_ProductsGetter($this->dbAdapter);
    }

    /**
     * Shopping Cart Summary
     */
    public function indexAction()
    {
    	$cartItems = $this->cacheHandler->getCacheId( Application_Model_CartCache::cartID );
    	
    	$amount = 0;
    	if ( !empty($cartItems) ) {
	    	foreach($cartItems as $key => $value) {

	    		$productRecord = $this->productsGetter->getProducts($key);
	    		
	    		$cartItems[$key]['name'] = $productRecord[0]['name'];
	    		$cartItems[$key]['price'] = $productRecord[0]['price'] * $cartItems[$key]['quantity'];
	    		
	    		$amount = $amount + $cartItems[$key]['price'];
	    	}
    	}
    	
    	$this->view->cartItems = $cartItems;
    	$this->view->amount = $amount;
    }

    /**
     * Add product to cart using cache
     * if the product is on the cart increment amout
     */
    public function addtocartAction()
    {
        $this->_helper->layout()->disableLayout();
        
    	if ( $this->getRequest()->isPost() ) {
    		$itemToAdd = $this->_request->getPost('id');

    		$cartItems = $this->cacheHandler->getCacheId( Application_Model_CartCache::cartID );
    		
    		if ($cartItems) {
    			$cartItems[$itemToAdd]['quantity'] = $cartItems[$itemToAdd]['quantity'] + 1;
    			$cartItems[$itemToAdd]['product_id'] = $itemToAdd;
    		} else {
    			$cartItems = array();
    			$cartItems[$itemToAdd] = array("product_id" => $itemToAdd, 'quantity' => 1);
    		}
    		$this->cacheHandler->save(Application_Model_CartCache::cartID, $cartItems);
    		
    		$this->view->itemAddedOK = 1;
    	}
    }

    /**
     * Remove product from the cart and redirect to cart
     */
    public function removeAction()
    {
    	$this->cacheHandler->cleanCacheID( $this->getRequest()->getQuery('id') );

    	$this->_redirector->redirect("index", "cart");
    }

    public function emptyAction()
    {
    	$this->cacheHandler->cleanCache();
    	
    	$this->_redirector->redirect("index", "cart");
    }

    public function summaryAction()
    {
    	$this->_helper->layout()->disableLayout();

    	$cartItems = $this->cacheHandler->getCacheId( Application_Model_CartCache::cartID );

    	$this->view->cartItems = $cartItems;
    }
}
