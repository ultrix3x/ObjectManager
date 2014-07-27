# ObjectManager
ObjectManager is an implementation of a kind of object factory with a cache
capability. The objects can be created by using the classname or both a name
and a classname. By calling the factory with the same name (or classname) it
returns the previously created instance.
The factory can be called in five different ways to allow the most flexibility
according to the desired programming pattern used. 


## Usage
```php
ObjectManager::Instance($name[, $class][, $action]);
```
* If classname is omitted then the name is used as classname.
* If action is true then the instance is recreated.
* If action is false then the instance is removed.


There are several different ways to get an instance
1. Static call
```php
$obj = ObjectManager::Instance($name, $class, $action);
```
2. Normal call
```php
  $objMgr = new ObjectManager();
  $obj = $objMgr->Instance($name, $class, $action);
```
3. Invoke method
```php
  $objMgr = new ObjectManager();
  $obj = $objMgr($name, $class, $action);
```
4. Use object variable
```php
  $objMgr = new ObjectManager();
  $obj = $objMgr->$name;
```
5. Use array access
```php
  $objMgr = new ObjectManager();
  $obj = $objMgr[$name];
```
x) For static and normal calls the class can be omitted. Then the classname
will be the same as the name for the instance.


There are several ways to remove a named instance
1. Static call
 ```php
 ObjectManager::Instance($name, false);
```
2. Normal call
```php
  $objMgr = new ObjectManager();
  ...
  $objMgr->Instance($name, false);
```
3. Use object variable
```php
  $objMgr = new ObjectManager();
  ...
  $objMgr->$name = false;
```
4. Use array access
```php
  $objMgr = new ObjectManager();
  ...
  $objMgr[$name] = false;
```
5. Use unset and array access
```php
  $objMgr = new ObjectManager();
  ...
  unset($objMgr[$name]);
```

There are several ways to recreate a named instance

1. Static call
```php
  ObjectManager::Instance($name, $class, true);
```
2. Normal call
```php
  $objMgr = new ObjectManager();
  ...
  $objMgr->Instance($name, $class, true);
```
3. Use object variable
```php
  $objMgr = new ObjectManager();
  ...
  $objMgr->$name = true;
```
4. Use array access
```php
  $objMgr = new ObjectManager();
  ...
  $objMgr[$name] = true;
```
x) For static and normal calls the class can be omitted. Then the classname
will be the same as the name for the instance.

x) There is a slight limitations when using object variable and array access to
create or recreate an instance since the name and classname will be the same.
If there is a need to create or recreate a named object where the name and
classname differs then please use static or normal calls.




## Examples
There are som simple examples (also found in the file mentioned just before the
example code.

There is an example file called examples_wild.php that covers a few more
different ways of usage.