<?php

/**
 * Cache Handler
 * 
 * @author Aleksandar Simonovic
 */
class Application_Model_CacheHandler
{
	/**
	 * @var Zend_Cache
	 */
	protected $cache;
	
	public function __construct()
	{
		$this->cache = Zend_Cache::factory(
				'Output',
				'File',
				array(),
				array()
		);
	}
	
	/**
	 * Save serialized data into a cache ID
	 * 
	 * @param string $id
	 * @param string $data
	 */
	public function save($id, $data)
	{
		$this->cache->load($id);
		$this->cache->save( serialize($data) );
	}
	
	/**
	 * Return unserialized data from cache passing the cache ID
	 * 
	 * @param string $id
	 */
	public function getCacheId($id)
	{
		$dataFromCache = $this->cache->load($id);
		if ($dataFromCache) {
			return unserialize($dataFromCache);
		}
		
		return false;
	}
	
	/**
	 * Remove data from a cache ID
	 * 
	 * @param string $id
	 */
	public function cleanCacheID($id)
	{
		$dataFromCache = $this->getCacheId( Application_Model_CartCache::cartID );
		
		if ($dataFromCache) {
			foreach ($dataFromCache as $key => $value) {
				if ($value['product_id'] == $id) {
					unset($dataFromCache[$key]);
					$this->cache->save( serialize($dataFromCache) );
					break;
				}
			}
		}
	}
	
	/**
	 * Clean all cache records 
	 */
	public function cleanCache()
	{
		$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
		
		return $this->cache;
	}
}
