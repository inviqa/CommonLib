<?php
/**
 * Cl_Autoload
 *
 * @category Cl
 * @package Cl_Autoload
 * @author Mikko Koppanen <mikko@ibuildings.com>
 * @author Lorenzo Alberton <lorenzo@ibuildings.com>
 */
class Cl_Autoload {
	private static $shm = false;
	private static $cache = array();
    
    /**
     * Registers the autoloading
     * 
     * @return void
     */
    public static function registerAutoload() {
        spl_autoload_register(array('Cl_Autoload', 'autoload'));
    }
    
    /**
     * Autoloading method
     * 
     * @param $className
     * @return void
     */
    public static function autoload($className){
        /* Do not try to load again if the class exists */
        if (class_exists($className, false)) {
            return;
        }
        
        if (strncmp($className, 'Zend_', 5) === 0) {
        	$key = 'internal_autoload::' . $className;
        } else {
	        if (defined('APPLICATION_PATH')) {
    	        $key = md5(APPLICATION_PATH) . '_autoload::' . $className;
        	} else {
            	$key = 'unknown_autoload::' . $className;
        	}
        }
        
        /* Would be more polite to use the cache abstraction but it might not be present
         * when this piece of code is executed for the first times */
        if ((($file = self::shmCacheFetch($key)) === false) || ($file === null)) {
            $classFile = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
            /* stream_resolve_include_path would be a lot nicer,
             * but at the time of writing it's only in HEAD. */
            if (function_exists('stream_resolve_include_path')) {
                $file = stream_resolve_include_path($classFile);
            } else {
                foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
                    if (file_exists($path . '/' . $classFile)) {
                        $file = $path . '/' . $classFile;
                        break;
                    }
                }
            }
            /* Store the failure in case we are not in debug */
            if ($file === false) {
                if (defined('CL_DEBUG')) {
                    if (CL_DEBUG === false) {
                        self::shmCacheStore($key, null, 86400);
                    }
                } else {
                    self::shmCacheStore($key, null, 86400);
                }
            } else {
                self::shmCacheStore($key, $file, 86400);
            }
        }
        /* If file is found, store it into the cache, classname <-> file association */
        if (($file !== false) && ($file !== null)) {
            include $file;
        }
    }

	/**
	 * Retrieve a value from the shared memory cache
	 * - this would benefit a nicer cache abstraction
	 *
	 * @param string $key 
	 * @return mixed value
	 */
	protected static function shmCacheFetch($key) {
		self::findShm();

		if(self::$shm == 1) {
			return zend_shm_cache_fetch($key);
		} else if(self::$shm == 2 ) {
			return apc_fetch($key);
		} else {
			return isset(self::$cache[$key]) ? self::$cache[$key] : null;
		}
	}
	

	/**
	 * Store a key in the shared memory cache
	 *
	 * @param string $key 
	 * @param string $value 
	 * @param int $length 
	 * @return mixed
	 */
	protected static function shmCacheStore($key, $value, $length = 0) {
		self::findShm();
		
		if(self::$shm == 1) {
			return zend_shm_cache_store($key, $value, $length);
		} else if(self::$shm == 2 ) {
			return apc_store($key, $value);
		} else {
			self::$cache[$key] = $value;
		}
	}
	
	/**
	 * Find which SHM function to use, currently support Zend Server and APC.
	 *
	 * @return void
	 */
	protected static function findShm() {
		if(self::$shm == false) {
			if(function_exists('zend_shm_cache_store')) {
				self::$shm = 1;
			} else if(function_exists('apc_store')) {
				self::$shm = 2;
			} else {
				// no SHM available, use local static cache.
				self::$shm = -1;
			}
		}
	}
}
