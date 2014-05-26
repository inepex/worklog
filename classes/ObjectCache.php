<?php

/**
 * Class ObjectCache provides caching of database objects.
 */
abstract class ObjectCache {

	/**
	 * @var array The object cache array with 'id' -> 'object' structure.
	 */
	protected static $objectCache = array();

	/**
	 * Checks the given id for a cached object.
	 * @param $id int The id to search for.
	 * @return bool True if the given id exists in the cache.
	 */
	protected static function isCached($id) {
		return isset(self::$objectCache[$id]);
	}

	/**
	 * Get the cached object for the given id.
	 * @param $id int The id of the object.
	 * @return mixed The cached object.
	 */
	protected static function getCached($id) {
		return self::$objectCache[$id];
	}

	/**
	 * Cache the given object for later usage.
	 * @param $id int The id of the object to cache.
	 * @param $object The object to cache.
	 */
	protected static function cache($id, $object) {
		self::$objectCache[$id] = $object;
	}

	/**
	 * Set the current object from a cached copy.
	 * @param $object mixed The cached object.
	 * @return mixed Nothing.
	 */
	abstract protected function setFromCache($object);

}
