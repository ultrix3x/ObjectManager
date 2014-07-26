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

// Create an instance of the ObjectManager
$objMgr = new ObjectManager();

// Create an instance of Test through the __invoke magic function
$t1 = $objMgr('Test'); // Prints "Constructed Test 1"
$t1->Hello(); // Prints "Hello, World!" and "This is object 1"

// Get the instance called Test through the __invoke magic function
$t2 = $objMgr('Test'); // Prints noting
$t2->Hello(); // Prints "Hello, World!" and "This is object 1"

// Recreate the named instance called Test through the __invoke magic function
$t3 = $objMgr('Test', true); // Prints "Constructed Test 2"
$t3->Hello(); // Prints "Hello, World!" and "This is object 2"

?>