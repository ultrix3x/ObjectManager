<?php
include('../objectmanager.php');

class Test {
  protected static $idx = 0;
  protected $index;
  
  function __construct() {
    static::$idx++;
    $this->index = static::$idx; 
    echo "Constructed ".__CLASS__." ".static::$idx.PHP_EOL;
  }
  
  function Hello() {
    echo "Hello, world!".PHP_EOL;
    echo "This is object ".$this->index.PHP_EOL;
  }
}

// Create an instance of Test
$t1 = ObjectManager::Instance('Test'); // Prints "Constructed Test 1"
$t1->Hello(); // Prints "Hello, World!" and "This is object 1"

// Get the instance called Test
$t2 = ObjectManager::Instance('Test'); // Prints noting
$t2->Hello(); // Prints "Hello, World!" and "This is object 1"

// Recreate the named instance called Test
$t3 = ObjectManager::Instance('Test', true); // Prints "Constructed Test 2"
$t3->Hello(); // Prints "Hello, World!" and "This is object 2"

?>