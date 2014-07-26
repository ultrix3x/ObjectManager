<?php
/*
 * ObjectManager
 * Version: 1.0.0
 * 
 * Copyright 2011, Thomas Björk
 * Contact: tb@ultrix.se
 *  * License: LGPL v3
 *   - http://www.gnu.org/licenses/lgpl-3.0-standalone.html
 *   - http://www.gnu.org/licenses/lgpl-3.0.txt
 * 
 * ObjectManager is an implementation of a kind of object factory with a cache
 * capability. The objects can be created by using the classname or both a name
 * and a classname. By calling the factory with the same name (or classname) it
 * returns the previously created instance.
 * The factory can be called in five different ways to allow the most
 * flexibility according to the desired programming pattern used.
 * 
 */

class ObjectManager implements ArrayAccess {
  // simple singleton implementation for the static usage
  protected static $manager = null;
  // an array containing all named objects created by this function
  protected $objectList;
    
  function __construct() {
    // Create an empty object list
    $this->objectList = array();
    // If this is the first time an instance is created then create an instance to use by the static methods
    if(static::$manager === null) {
      static::$manager = $this;
    }
  }
  
  function __destruct() {
    // Remove all objects allocated by the manager
    $keys = array_keys($this->objectList);
    foreach($keys as $key) {
      unset($this->objectList[$key]);
    }
    $this->objectList = null;
  }
  
  function __get($property) {
    // Call _Instance with the property given as the classname
    return $this->_Instance(array($property));
  }
  
  function __set($property, $value) {
    // The the given value is true or false then call _Instance with the given property as the classname
    if(($value === false) || ($value === true)) {
      $this->_Instance(array($property, $value));
    }
  }
  
  function offsetGet($offset) {
    // Call _Instance with the offset given as the classname
    return $this->_Instance(array($offset));
  }
  
  function offsetSet($offset, $value) {
    // The the given value is true or false then call _Instance with the given offset as the classname
    if(($value === false) || ($value === true)) {
      $this->_Instance(array($offset, $value));
    }
  }

  function offsetExists($offset) {
    // Check if the given offset is a named object
    return isset($this->objectList[$offset]);
  }
  
  function offsetUnset($offset) {
    // Remove the named object that has the same name as the given offset
    if(isset($this->objectList[$offset])) {
      unset($this->objectList[$offset]);
    }
  }
  
  function __Invoke() {
    // Collect all arguments used to call the __Invoke function
    $args = func_get_args();
    // Call _Instance with the given arguments
    return $this->_Instance($args);
  }
  
  function __call($function, $args) {
    // Handle all dynamic calls to the instance
    switch($function) {
      // If the function Instance is called then redirect the call to _Instance
      case 'Instance': return $this->_Instance($args);
    }
  }
  
  static function __callStatic($function, $args) {
    // Handle all static calls to the class
    $process = false;
    switch($function) {
      case 'Instance':
      // If the function Instance is called then prepare a structure to be handled further down
      $process = array('static'=>true, 'function'=>'__call');
      break;
    }
    if($process !== false) {
      if(isset($process['static']) && ($process['static'] === true)) {
        // Make wure that the static manager is available
        if(static::$manager === null) {
          $class = get_called_class();
          new $class();
        }
        // Should the __call function be used
        if($process['function'] == '__call') {
          // Call the __call function on the static manager with the same arguments used to call this function
          return static::$manager->__call($function, $args);
        }
      }
    }
  }
  
  // _Instance is the main function used in the manager to return an instance of the given class
  protected function _Instance($args) {
    // Use the first argument as a name for the object
    if(isset($args[0])) {
      $objectName = $args[0];
    } else {
      $objectName = false;
    }
    // Use the second argument as the classname to use
    // If no classname is used then use the object name as classname
    if(isset($args[1])) {
      $className = $args[1];
    } else {
      $className = $objectName;
    }
    // If the first argument was given (and not equal to false) then do some magic
    if($objectName !== false) {
      if($className === false) {
        // If classname is false then the named object should be removed if it exists
        if(isset($this->objectList[$objectName])) {
          unset($this->objectList[$objectName]);
        }
      } elseif($className === true) {
        // If the classname is true then the named object should be recreated ...
        if(isset($this->objectList[$objectName])) {
          unset($this->objectList[$objectName]);
        }
        // ... using the object name as classname if the class exists
        if(class_exists($objectName)) {
          $this->objectList[$objectName] = new $objectName();
        }
        return $this->objectList[$objectName];
      } else {
        if(isset($args[2]) && ($args[2] === true)) {
          // If there is a third argument and this is equal to true then
          // any existing object with the given name should be deleted ...
          if(isset($this->objectList[$objectName])) {
            unset($this->objectList[$objectName]);
          }
          // ... and if the given classname exists ...
          if(class_exists($className)) {
            // ... recreated
            $this->objectList[$objectName] = new $className();
          }
        } elseif(!isset($this->objectList[$objectName])) {
          // If the named object shouldn't be recreated and if the classname exists ...
          if(class_exists($className)) {
            // ... then create a new instance of the named object from the given classname
            $this->objectList[$objectName] = new $className();
          }
        }
        // If the named object exists ...
        if(isset($this->objectList[$objectName])) {
          // then return it to the caller
          return $this->objectList[$objectName];
        }
      }
    }
    // If no object has been found or if no object should be returned then return null
    return null;
  }
  
}

?>