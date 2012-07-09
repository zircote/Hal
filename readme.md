# Hal
[![Build Status](https://secure.travis-ci.org/zircote/Hal.png)](http://travis-ci.org/zircote/Hal)

 * [HAL Specification](http://stateless.co/\Hal\specification.html)
 * [Hal Specification on Github](https://github.com/mikekelly/\Hal\specification)
 * [JSON Linking With HAL](http://blog.stateless.co/post/13296666138/json-linking-with-hal)
 * [Linking In Json](http://www.mnot.net/blog/2011/11/25/linking_in_json)
 * [Examples of HAL](https://gist.github.com/2289546)
 * [HAL on Google Groups](https://groups.google.com/d/forum/hal-discuss)

```php
<?php
use Hal\Resource,
    Hal\Link;
/* Create a new Resource Parent */
$parent = new Resource('/dogs');
/* Add any relevent links */
$parent->setLink(new Link('/dogs?q={text}', 'search'));
$dogs[1] =  new Resource('/dogs/1');
$dogs[1]->setData(
    array(
        'id' => '1', 
        'name' => 'tiber', 
        'color' => 'black'
    )
);
$dogs[2] =  new Resource(
    '/dogs/2',array(
        'id' => '2', 
        'name' => 'sally', 
        'color' => 'white'
    )
);
$dogs[3] =  new Resource(
    '/dogs/3',array(
        'id' => '3', 
        'name' => 'fido', 
        'color' => 'gray'
    )
);
/* Add the embedded resources */
foreach ($dogs as $dog) {
    $parent->setEmbedded('dog', $dog);
}
echo (string) $parent;
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
		"dog":[
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
## Generating XML output

```php
<?php
echo $parent->getXML()->asXML();
```
### Result:
```xml
<?xml version="1.0"?>
<resource href="/dogs">
    <link href="/dogs?q={text}" rel="search" />
    <resource href="/dogs/1" rel="dog">
        <id>1</id>
        <name>tiber</name>
        <color>black</color>
    </resource>
    <resource href="/dogs/2" rel="dog">
        <id>2</id>
        <name>sally</name>
        <color>white</color>
    </resource>
    <resource href="/dogs/3" rel="dog">
        <id>3</id>
        <name>fido</name>
        <color>gray</color>
    </resource>
</resource>
```
