# Zircote_Hal
 * http://stateless.co/hal_specification.html
 * http://blog.stateless.co/post/13296666138/json-linking-with-hal

```php
<?php
/* Create a new Resource Parent */
$parent = new Zircote_Hal_Resource('/dogs');
/* Add any relevent links */
$parent->setLink(new Zircote_Hal_Link('/dogs?q={text}'), 'search');
$dogs[1] =  new Zircote_Hal_Resource('/dogs/1');
$dogs[1]->setData(array('id' => '1', 'name' => 'tiber', 'color' => 'black'));
$dogs[2] =  new Zircote_Hal_Resource('/dogs/2');
$dogs[2]->setData(array('id' => '2', 'name' => 'sally', 'color' => 'white'));
$dogs[3] =  new Zircote_Hal_Resource('/dogs/3');
$dogs[3]->setData(array('id' => '3', 'name' => 'fido', 'color' => 'gray'));
/* Add the embedded resources */
foreach ($dogs as $dog) {
    $parent->setEmbedded('dogs', $dog);
}
echo Zend_Json::prettyPrint($parent);
```

### Result: 

```javascript
{
  "_links":{
		"self":{
			"href":"\/dogs"
		},
		"search":{
			"href":"\/dogs?q={text}"
		}
	},
	"_embedded":{
		"dogs":[
			{
				"_links":{
					"self":{
						"href":"\/dogs\/1"
					}
				},
				"id":"1",
				"name":"tiber",
				"color":"black"
			},
			{
				"_links":{
					"self":{
						"href":"\/dogs\/2"
					}
				},
				"id":"2",
				"name":"sally",
				"color":"white"
			},
			{
				"_links":{
					"self":{
						"href":"\/dogs\/3"
					}
				},
				"id":"3",
				"name":"fido",
				"color":"gray"
			}
		]
	}
}
```