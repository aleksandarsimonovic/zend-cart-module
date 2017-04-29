<?php

/**
 * Products Getter from Database
 * 
 * @author Aleksandar Simonovic
 */
class Application_Model_ProductsGetter
{
	private $dbAdapter;
	
	/**
	 * 
	 * @param Zend_Db_Adapter_Abstract $dbAdapter
	 */
	public function __construct(Zend_Db_Adapter_Abstract $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
	}
	
	/**
	 * Get Products
	 * 
	 * @param number $id
	 */
	public function getProducts($id = null)
	{
		$select = $this->dbAdapter->select()->from('products');

		if ($id) {
			$select = $select->where("id = '$id' ");
		}

    	return $select->query()->fetchAll();
	}
}