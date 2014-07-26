<?php
// Include ObjectManager
include_once('../objectmanager.php');

/*
 * Simple test class to demonstrate usage of ObjectManager
 */
class Test {
  function __construct() {
    echo "Creating test".PHP_EOL;
  }
  
  function Hello() {
    echo "Hello, world!".PHP_EOL;
  }
}

// Create instance of ObjectManager
$om = new ObjectManager();

// Get instance of class Test by using normal call
$o = $om->Instance('Test');
$o->Hello();

// Get a named instance of class Test by using normal call
// $o2 is not the same object as $o
$o2 = $om->Instance('Test2', 'Test');
$o2->Hello();

// Reuse the object called Test2
$o3 = $om->Instance('Test2'); // This doesn't generate any call to the constructor of Test
$o3->Hello();

// Reset the named instance called Test (which was the first instance created)
$om->Instance('Test', false);

// By getting the instance called Test once again the constructor is called
$o4 = $om->Instance('Test');
$o4->Hello();

// Replace the named object Test2 with a new instance
$o5 = $om->Instance('Test2', 'Test', true);
$o5->Hello();

// Recreate the named instance called Test by using then name as an object variable
$om->Test = true;

// Reset the named instance called Test by using then name as an object variable
$om->Test = false;

// Get the named instance called Test by using the name as an object variable
$o6 = $om->Test;
$o6->Hello();

// Get the named instance called Test by using ObjectManager as an array
$o7 = $om['Test'];
$o7->Hello();

// Recreate the named instance called Test by using ObjectManager as an array
$om['Test'] = true;

// Get the named instace called Test2 by using ObjectManager statically
$o8 = ObjectManager::Instance('Test2');
$o8->Hello();

// Get the named instace called Test2 by using instance of ObjectManager statically
$o9 = $om::Instance('Test2');
$o9->Hello();

// Reset the named instance called Test2 by using ObjectManager as an array and unset
unset($om['Test2']);
 
// Get the named instace called Test2 by using instance of ObjectManager statically
$o10 = $om::Instance('Test2'); // returns null since a class named Test2 couldn't be found
echo serialize($o10).PHP_EOL;
?>