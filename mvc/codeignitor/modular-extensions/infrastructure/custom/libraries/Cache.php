<?php

/**
 * Cache library
 * Set and get items from the cache
 * Currently uses memcache
 */
class Cache {

	// memcache server & port
	protected $_memcache_server = 'app2.brainhost.com';
	protected $_memcache_port   = '11211';
	
	// memcache object and whether it's connected
	protected $_memcache        = NULL;
	protected $_connected       = FALSE;

	/**
	 * Constructor; connect to memcache server 
	 */
	function __construct()
	{

		$this->connect($this->_memcache_server, $this->_memcache_port);

	}

	/**
	 * Disconnect from memcache when library closes
	 */
	function __destruct()
	{
		$this->disconnect();
	}

	/**
	 * Return whether or not memcache is connected
	 * Should be updated to use memcache internal functions instead of a class var
	 * @return boolean Whether memcache is connected
	 */
	protected function _is_connected()
	{

		return $this->_connected;

	}

	/**
	 * Connect to memcache server
	 * @param  string $server Memcache server address
	 * @param  string $port   Memcache server port
	 * @return boolean        Whether connection was successful
	 */
	function connect($server, $port) 
	{

		if ( ! class_exists('Memcache')):

			return FALSE;

		endif;
		
		$this->_memcache = new Memcache;
		$this->_connected = @$this->_memcache->connect($server, $port);

		return $this->_connected;
	}

	/**
	 * Disconnect from memcache server
	 * @return boolean Whether disconnection was successful
	 */
	function disconnect()
	{

		if ($this->_is_connected()):

			$this->_memcache->close();

		endif;

		$this->_connected = FALSE;

	}

	/**
	 * Clear all items from the cache
	 * @return boolean Whether flush was successful
	 */
	function flush()
	{

		if ( ! $this->_is_connected()):

			return FALSE;

		endif;

		return $this->_memcache->flush();


	}

	/**
	 * Return an item from the cache
	 * @param  string $key Which item to retrieve
	 * @return mixed      $key's value in cache; FALSE if $key is not found
	 */
	function get($key, $compressed = FALSE)
	{

		if ( ! $this->_is_connected()):

			return FALSE;

		endif;

		return $this->_memcache->get($key, ($compressed ? MEMCACHE_COMPRESSED : FALSE));
	}

	/**
	 * Stores data associated with key in cache
	 * Does not care if the key already exists
	 * @param string  $key    The key to associate data with
	 * @param mixed  $data   The data to store
	 * @param integer $expiry Number of seconds to keep the cached item
	 * @param boolean $compress Whether to pass the MEMCACHE_COMPRESSED flag
	 * @return boolean Whether the method was successful
	 */
	function set($key, $data, $expiry = 86400, $compress = FALSE)
	{

		if ( ! $this->_is_connected()):

			return FALSE;

		endif;

		return $this->_memcache->set($key, $data, ($compress ? MEMCACHE_COMPRESSED : FALSE), $expiry);

	}


	/**
	 * Adds an item to the cache
	 * Only adds if the key does not alread exist
	 * Useful to prevent multiple set() calls from
	 * overriding each other
	 * @param string  $key      Key to associate data with
	 * @param mixed  $data     Data to store
	 * @param integer $expiry   Number of seconds to keep the cache
	 * @param boolean $compress Whether to pass the MEMCACHE_COMPRESSED flag
	 * @return boolean Whether the method was successful
	 */
	function add($key, $data, $expiry = 86400, $compress = FALSE)
	{

		if ( ! $this->_is_connected()):

			return FALSE;

		endif;

		return $this->_memcache->add($key, $data, ($compress ? MEMCACHE_COMPRESSED : FALSE), $expiry);

	}


	/**
	 * Replaces an item in the cache with new data
	 * Only works if the item currently exists
	 * @param string  $key      Key to associate data with
	 * @param mixed  $data     Data to store
	 * @param integer $expiry   Number of seconds to keep the cache
	 * @param boolean $compress Whether to pass the MEMCACHE_COMPRESSED flag
	 * @return boolean Whether the method was successful
	 */
	function replace($key, $data, $expiry = 86400, $compress = FALSE)
	{

		if ( ! $this->_is_connected()):

			return FALSE;

		endif;

		return $this->_memcache->replace($key, $data, ($compress ? MEMCACHE_COMPRESSED : FALSE), $expiry);

	}

	/**
	 * Remove an item from the cache
	 * @param  string $key Which item to remove
	 * @return boolean      Whether the deletion was successful
	 */
	function delete($key)
	{

		if ( ! $this->_is_connected()):

			return FALSE;

		endif;

		return $this->_memcache->delete($key);

	}
}