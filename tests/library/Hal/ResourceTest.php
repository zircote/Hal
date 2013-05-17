<?php
namespace Hal\Tests;

/**
 * @category Hal
 * @package Hal
 * @subpackage Hal\Tests
 */
use Hal\Resource;
use Hal\Link;

/**
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * Copyright [2012] [Robert Allen]
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Link test case.
 */
class ResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructWithLinkObject()
    {
        $self = new Link('/dogs', 'self');

        $parent = new Resource($self);

        $actual = json_decode($parent);


        $JSON = <<<EOF
{
   "_links":{
      "self":{
         "href":"\/dogs"
      }
   }
}
EOF;

        $expected = json_decode($JSON);

        $this->assertEquals($expected, $actual);	
    }

    public function testSetSingleLink()
    {
        $parent = new Resource('/dogs');
        $parent->setLink(new Link('/dogs?q={text}', 'search'));

        $actual = json_decode($parent);

        $JSON = <<<EOF
{
   "_links":{
      "self":{
         "href":"\/dogs"
      },
      "search":{
         "href":"\/dogs?q={text}"
      }
   }
}
EOF;

        $expected = json_decode($JSON);
        $this->assertEquals($expected, $actual);
    }

    public function testSetSingleLinkPlural()
    {
        $parent = new Resource('/dogs');
        $parent->setLink(new Link('/dogs?q={text}', 'search'), false, true);

        $actual = json_decode($parent);

        $JSON = <<<EOF
{
   "_links":{
      "self":{
         "href":"\/dogs"
      },
      "search": [
         {
            "href":"\/dogs?q={text}"
         }
      ]
   }
}
EOF;

        $expected = json_decode($JSON);
        $this->assertEquals($expected, $actual);
    }

    public function testSetLinkMultiple()
    {
        $parent = new Resource('/dogs');
        $parent->setLink(new Link('/dogs?q={text}', 'search'));
        $parent->setLink(new Link('/dogs?q={text}&limit={limit}', 'search'));

        $actual = json_decode($parent);

        $JSON = <<<EOF
{
   "_links":{
      "self":{
         "href":"\/dogs"
      },
      "search":[{
         "href":"\/dogs?q={text}"
      },{
         "href":"\/dogs?q={text}&limit={limit}"
      }]
   }
}
EOF;

        $expected = json_decode($JSON);
        $this->assertEquals($expected, $actual);
    }

    public function testSetLinkMultipleSingular()
    {
        $parent = new Resource('/dogs');
        $parent->setLink(new Link('/dogs?q={text}', 'search'));
        $parent->setLink(new Link('/dogs?q={text}&limit={limit}', 'search'), true);

        $actual = json_decode($parent);

        $JSON = <<<EOF
{
   "_links":{
      "self":{
         "href":"\/dogs"
      },
      "search":{
         "href":"\/dogs?q={text}&limit={limit}"
      }
   }
}
EOF;

        $expected = json_decode($JSON);
        $this->assertEquals($expected, $actual);
    }

    public function testSetLinkMultipleSingularPlural()
    {
        $parent = new Resource('/dogs');
        $parent->setLink(new Link('/dogs?q={text}', 'search'));
        $parent->setLink(new Link('/dogs?q={text}&limit={limit}', 'search'), true, true);

        $actual = json_decode($parent);

        $JSON = <<<EOF
{
   "_links":{
      "self":{
         "href":"\/dogs"
      },
      "search":{
         "href":"\/dogs?q={text}&limit={limit}"
      }
   }
}
EOF;

        $expected = json_decode($JSON);
        $this->assertEquals($expected, $actual);
    }

    public function testSetLinkMultiplePlural()
    {
        $parent = new Resource('/dogs');
        $parent->setLink(new Link('/dogs?q={text}', 'search'), false, true);
        $parent->setLink(new Link('/dogs?q={text}&limit={limit}', 'search'), false, true);

        $actual = json_decode($parent);

        $JSON = <<<EOF
{
   "_links":{
      "self":{
         "href":"\/dogs"
      },
      "search":[{
         "href":"\/dogs?q={text}"
      },{
         "href":"\/dogs?q={text}&limit={limit}"
      }]
   }
}
EOF;

        $expected = json_decode($JSON);
        $this->assertEquals($expected, $actual);
    }

    public function testSetLinksMultiple()
    {
        $parent = new Resource('/dogs');
        $links = array(
          new Link('/dogs?q={text}', 'search'),
          new Link('/dogs?q={text}&limit={limit}', 'search')
        );
        $parent->setLinks($links);

        $actual = json_decode($parent);

        $JSON = <<<EOF
{
   "_links":{
      "self":{
         "href":"\/dogs"
      },
      "search":[{
         "href":"\/dogs?q={text}"
      },{
         "href":"\/dogs?q={text}&limit={limit}"
      }]
   }
}
EOF;

        $expected = json_decode($JSON);
        $this->assertEquals($expected, $actual);
    }

    public function testSetLinksMultipleSingluar()
    {
        $parent = new Resource('/dogs');
        $links = array(
          new Link('/dogs?q={text}', 'search'),
          new Link('/dogs?q={text}&limit={limit}', 'search')
        );
        $parent->setLinks($links, true);

        $actual = json_decode($parent);

        $JSON = <<<EOF
{
   "_links":{
      "self":{
         "href":"\/dogs"
      },
      "search":{
         "href":"\/dogs?q={text}&limit={limit}"
      }
   }
}
EOF;

        $expected = json_decode($JSON);
        $this->assertEquals($expected, $actual);
    }

    public function testSetLinksMultipleSingluarPlural()
    {
        $parent = new Resource('/dogs');
        $links = array(
          new Link('/dogs?q={text}', 'search'),
          new Link('/dogs?q={text}&limit={limit}', 'search')
        );
        $parent->setLinks($links, true, true);

        $actual = json_decode($parent);

        $JSON = <<<EOF
{
   "_links":{
      "self":{
         "href":"\/dogs"
      },
      "search":{
         "href":"\/dogs?q={text}&limit={limit}"
      }
   }
}
EOF;

        $expected = json_decode($JSON);
        $this->assertEquals($expected, $actual);
    }

    public function testSetLinksMultiplePlural()
    {
        $parent = new Resource('/dogs');
        $links = array(
          new Link('/dogs?q={text}', 'search'),
          new Link('/dogs?q={text}&limit={limit}', 'search'),
          new Link('/dogs?page=2', 'next')
        );
        $parent->setLinks($links, false, true);

        $actual = json_decode($parent);

        $JSON = <<<EOF
{
   "_links":{
      "self":{
         "href":"\/dogs"
      },
      "search":[{
         "href":"\/dogs?q={text}"
      },{
         "href":"\/dogs?q={text}&limit={limit}"
      }],
      "next":[{
         "href":"\/dogs?page=2"
      }]
   }
}
EOF;

        $expected = json_decode($JSON);
        $this->assertEquals($expected, $actual);
    }

    /**
     * This tests adding an empty resource
     */
    public function testEmptyResource()
    {
        $fixture = <<<'EOF'
{
    "_links":{
        "self":{
            "href":"/dogs"
        },
        "search":{
            "href":"/dogs?q={text}"
        }
    }, "_embedded":{
        "dog":[]
    }
}
EOF;

        $parent = new Resource('/dogs');
        $parent->setLink(new Link('/dogs?q={text}', 'search'));

        $parent->setEmbedded('dog', null);

        $this->assertEquals(
            json_decode($fixture), json_decode((string)$parent)
        );
    }

    /**
     * This tests adding a resource with no self link
     */
    public function testEmptySelfLink()
    {
        $fixture = <<<'EOF'
{
    "data": "value"
}
EOF;

        $parent = new Resource('', array('data' => 'value'));

        $this->assertEquals(
            json_decode($fixture), json_decode((string) $parent)
        );
    }

    /**
     * This tests adding a resource with no self link
     */
    public function testEmptySelfLinkWithOthers()
    {
        $fixture = <<<'EOF'
{
    "_links":{
        "search":{
            "href":"/dogs?q={text}"
        }
    },
    "data": "value"
}
EOF;

        $parent = new Resource('', array('data' => 'value'));
        $parent->setLink(new Link('/dogs?q={text}', 'search'));

        $this->assertEquals(
            json_decode($fixture), json_decode((string) $parent)
        );
    }
    public function testJsonNumericConversion()
    {
        $fixture = <<<'EOF'
{
    "_links":{
        "search":{
            "href":"/dogs?q={text}"
        }
    },
    "data": 98765432
}
EOF;
        $fixture1 = <<<'EOF'
{
    "_links":{
        "search":{
            "href":"/dogs?q={text}"
        }
    },
    "data": "98765432"
}
EOF;

        $parent = new Resource('', array('data' => '98765432'));
        $parent->setLink(new Link('/dogs?q={text}', 'search'));
        // Default is off
        $this->assertEquals(
            json_decode($fixture1), json_decode((string) $parent)
        );
        $parent->setJsonNumericCheck(Resource::JSON_NUMERIC_CHECK_ON);
        // Active On
        $this->assertEquals(
            json_decode($fixture), json_decode((string) $parent)
        );
        $parent->setJsonNumericCheck(Resource::JSON_NUMERIC_CHECK_OFF);
        // Active Off
        $this->assertEquals(
            json_decode($fixture1), json_decode((string) $parent)
        );
        $parent->setJsonNumericCheck(Resource::JSON_NUMERIC_CHECK_ON);
        // State Returned
        $this->assertEquals(
            json_decode($fixture), json_decode((string) $parent)
        );
    }
}
