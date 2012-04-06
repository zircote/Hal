<?php
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
 * Hal_Link test case.
 */
class Hal_ResourceTest extends PHPUnit_Framework_TestCase
{
    public function testSetSingleLink()
    {
        $parent = new Hal_Resource('/dogs');
        $parent->setLink(new Hal_Link('/dogs?q={text}', 'search'));

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
        $parent = new Hal_Resource('/dogs');
        $parent->setLink(new Hal_Link('/dogs?q={text}', 'search'));
        $parent->setLink(new Hal_Link('/dogs?q={text}&limit={limit}', 'search'));

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
        $parent = new Hal_Resource('/dogs');
        $parent->setLink(new Hal_Link('/dogs?q={text}', 'search'));
        $parent->setLink(new Hal_Link('/dogs?q={text}&limit={limit}', 'search'), true);

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
