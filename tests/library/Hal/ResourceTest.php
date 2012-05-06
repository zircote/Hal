<?php
/**
 * @category Hal
 * @package Hal
 * @subpackage Hal\Tests
 */
namespace Hal\Tests;
use Hal\Resource,
    Hal\Link;
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
}
