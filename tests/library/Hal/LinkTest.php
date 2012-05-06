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
class LinkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        parent::tearDown();
    }
    public function testXML()
    {
        $parentRes = new Resource('/orders');
        $parentRes->setLink(
        new Link('/orders?page=2', 'next')
        )->setLink(
        new Link('/orders?id={order_id}', 'search')
        );
        $data = array(
        'total' => '30.00','currency' => 'USD',
        'status' => 'shipped','placed' => '2011-01-16',
        );
        $embedded1 = new Resource('/orders/123');
        $embedded1->setData($data)
        ->setLink(
        new Link(
        '/customer/bob','customer','Bob Jones <bob@jones.com>'
        )
        );
        $basketItems = array(
        array('sku' => 'ABC123','quantity' => 2,'price' => '9.50'),
        array('sku' => 'GFZ111','quantity' => 1,'price' => '11.00')
        );
        $basket = new Resource('/orders/123/basket');
        $basket->setData('items', $basketItems);
        $embedded1->setEmbedded('basket', $basket, true);
        //////////////////////////////////////////////
        $data = array(
        'total' => '20.00','currency' => 'USD',
        'status' => 'processing','placed' => '2011-01-16',
        );
        $embedded2 = new Resource('/orders/124');
        $embedded2->setData($data)
        ->setLink(
        new Link(
        '/customer/jen','customer','Jen Harris <jen@internet.com>'
        )
        );
        /////////////////////////////////////
        $basketItems = array(
        array('sku' => 'KLM222','quantity' => 1,'price' => "9.00"),
        array('sku' => 'HHI50','quantity' => 1,'price' => "11.00")
        );
        $basket2 = new Resource('/orders/124/basket');
        $basket2->setData('items', $basketItems);
        $embedded2->setEmbedded('basket', $basket2, true);

        $parentRes
        ->setEmbedded('order', $embedded1)
        ->setEmbedded('order', $embedded2);
        /* @export as XML
         *
         *
         */
        $parentRes->getXML()->asXML();

    }
    public function testBuild()
    {
        $parentRes = new Resource('/orders');
        $parentRes->setLink(
            new Link('/orders?page=2', 'next')
        )->setLink(
            new Link('/orders?id={order_id}', 'search')
        );
        $data = array(
            'total' => '30.00','currency' => 'USD',
            'status' => 'shipped','placed' => '2011-01-16',
        );
        $embedded1 = new Resource('/orders/123');
        $embedded1->setData($data)
            ->setLink(
                new Link(
                    '/customer/bob','customer','Bob Jones <bob@jones.com>'
                )
            );
        $basketItems = array(
            array('sku' => 'ABC123','quantity' => 2,'price' => '9.50'),
            array('sku' => 'GFZ111','quantity' => 1,'price' => '11.00')
        );
        $basket = new Resource('/orders/123/basket');
        $basket->setData('items', $basketItems);
        $embedded1->setEmbedded('basket', $basket, true);
        //////////////////////////////////////////////
        $data = array(
            'total' => '20.00','currency' => 'USD',
            'status' => 'processing','placed' => '2011-01-16',
        );
        $embedded2 = new Resource('/orders/124');
        $embedded2->setData($data)
        ->setLink(
            new Link(
                '/customer/jen','customer','Jen Harris <jen@internet.com>'
            )
        );
        /////////////////////////////////////
        $basketItems = array(
            array('sku' => 'KLM222','quantity' => 1,'price' => "9.00"),
            array('sku' => 'HHI50','quantity' => 1,'price' => "11.00")
        );
        $basket2 = new Resource('/orders/124/basket');
        $basket2->setData('items', $basketItems);
        $embedded2->setEmbedded('basket', $basket2, true);

        $parentRes
        ->setEmbedded('order', $embedded1)
        ->setEmbedded('order', $embedded2);
        $actual = json_decode($parentRes);
        $JSON =<<<EOF
{
  "_links": {
    "self": { "href": "/orders" },
    "next": { "href": "/orders?page=2" },
    "search": { "href": "/orders?id={order_id}" }
  },
  "_embedded": {
    "order": [
      {
        "_links": {
          "self": { "href": "/orders/123" },
          "customer": { "href": "/customer/bob", "title": "Bob Jones <bob@jones.com>" }
        },
        "total": "30.00",
        "currency": "USD",
        "status": "shipped",
        "placed": "2011-01-16",
        "_embedded": {
          "basket": {
            "_links": {
              "self": { "href": "/orders/123/basket" }
            },
            "items": [
              {
                "sku": "ABC123",
                "quantity": 2,
                "price": "9.50"
              },{
                "sku": "GFZ111",
                "quantity": 1,
                "price": 11
              }
            ]
          }
        }
      },{
        "_links": {
          "self": { "href": "/orders/124" },
          "customer": { "href": "/customer/jen", "title": "Jen Harris <jen@internet.com>" }
        },
        "total": "20.00",
        "currency": "USD",
        "status": "processing",
        "placed": "2011-01-16",
        "_embedded": {
          "basket": {
            "_links": {
              "self": { "href": "/orders/124/basket" }
            },
            "items": [
              {
                "sku": "KLM222",
                "quantity": 1,
                "price": "9.00"
              },{
                "sku": "HHI50",
                "quantity": 1,
                "price": 11.00
              }
            ]
          }
        }
      }
    ]
  }
}
EOF;
        $expected = json_decode($JSON);
        $this->assertEquals($expected, $actual);
    }
    /**
     * @group Simple
     */
    public function testSimple()
    {
        $parent = new Resource('/dogs');
        /* Add any relevent links */
        $parent->setLink(new Link('/dogs?q={text}', 'search'));
        $dogs[1] =  new Resource('/dogs/1');
        $dogs[1]->setData(array('id' => '1', 'name' => 'tiber', 'color' => 'black'));
        $dogs[2] =  new Resource('/dogs/2',array('id' => '2', 'name' => 'sally', 'color' => 'white'));
        $dogs[3] =  new Resource('/dogs/3',array('id' => '3', 'name' => 'fido', 'color' => 'gray'));
        /* Add the embedded resources */
        foreach ($dogs as $dog) {
            $parent->setEmbedded('dog', $dog);
        }
    }
}

