<?php

/**
 * Class ObjectCache provides caching of database objects.
 */
abstract class ObjectCache {

	/**
	 * @var array The object cache array with 'id' -> 'object' structure.
	 */
	protected static $objectCache = array();

    protected static function isCachedS($id, $objectName) {
        return isset(self::$objectCache[$objectName . '-' . $id]);
    }

    protected static function getCachedS($id, $objectName) {
        return self::$objectCache[$objectName . '-' . $id];
    }
	/**
	 * @var string The name of the child object.
	 */
	protected $objectName;

	/**
	 * Checks the given id for a cached object.
	 * @param $id int The id to search for.
	 * @return bool True if the given id exists in the cache.
	 */
	protected function isCached($id)
    {
        return isset(self::$objectCache[$this->objectName . '-' . $id]);
    }

	/**
	 * Get the cached object for the given id.
	 * @param $id int The id of the object.
	 * @return mixed The cached object.
	 */
	protected function getCached($id) {
		return self::$objectCache[$this->objectName . '-' . $id];
	}

	/**
	 * Cache the given object for later usage.
	 * @param $id int The id of the object to cache.
	 * @param $object The object to cache.
	 */
	protected function cache($id, $object) {
		self::$objectCache[$this->objectName . '-' . $id] = $object;
	}

	/**
	 * Set the current object from a cached copy.
	 * @param $object mixed The cached object.
	 * @return mixed Nothing.
	 */
	abstract protected function setFromCache($object);

}
