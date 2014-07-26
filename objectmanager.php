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
  protected static $manager = null;
  protected $objectList;
    
  function __construct() {
    $this->objectList = array();
    if(static::$manager === null) {
      static::$manager = $this;
    }
  }
  
  function __destruct() {
    $keys = array_keys($this->objectList);
    foreach($keys as $key) {
      unset($this->objectList[$key]);
    }
    $this->objectList = null;
  }
  
  function __get($property) {
    return $this->_Instance(array($property));
  }
  
  function __set($property, $value) {
    if(($value === false) || ($value === true)) {
      $this->_Instance(array($property, $value));
    }
  }
  
  function offsetGet($offset) {
    return $this->_Instance(array($offset));
  }
  
  function offsetSet($offset, $value) {
    if(($value === false) || ($value === true)) {
      $this->_Instance(array($offset, $value));
    }
  }

  function offsetExists($offset) {
    return isset($this->objectList[$offset]);
  }
  
  function offsetUnset($offset) {
    unset($this->objectList[$offset]);
  }
  
  function __Invoke() {
    $args = func_get_args();
    return $this->_Instance($args);
  }
  
  function __call($function, $args) {
    switch($function) {
      case 'Instance': return $this->_Instance($args);
    }
  }
  
  static function __callStatic($function, $args) {
    $process = false;
    switch($function) {
      case 'Instance':
      $process = array('static'=>true, 'function'=>'__call');
      break;
    }
    if($process !== false) {
      if(isset($process['static']) && ($process['static'] === true)) {
        if(static::$manager === null) {
          $class = get_called_class();
          new $class();
        }
        if($process['function'] == '__call') {
          return static::$manager->__call($function, $args);
        }
      }
    }
  }
  
  protected function _Instance($args) {
    if(isset($args[0])) {
      $objectName = $args[0];
    } else {
      $objectName = false;
    }
    if(isset($args[1])) {
      $className = $args[1];
    } else {
      $className = $objectName;
    }
    if($objectName !== false) {
      if($className === false) {
        if(isset($this->objectList[$objectName])) {
          unset($this->objectList[$objectName]);
        }
      } elseif($className === true) {
        if(isset($this->objectList[$objectName])) {
          unset($this->objectList[$objectName]);
        }
        if(class_exists($objectName)) {
          $this->objectList[$objectName] = new $objectName();
        }
        return $this->objectList[$objectName];
      } else {
        if(isset($args[2]) && ($args[2] === true)) {
          if(isset($this->objectList[$objectName])) {
            unset($this->objectList[$objectName]);
          }
          if(class_exists($className)) {
            $this->objectList[$objectName] = new $className();
          }
        } elseif(!isset($this->objectList[$objectName])) {
          if(class_exists($className)) {
            $this->objectList[$objectName] = new $className();
          }
        }
        if(isset($this->objectList[$objectName])) {
          return $this->objectList[$objectName];
        }
      }
    }
    return null;
  }
  
}

?>