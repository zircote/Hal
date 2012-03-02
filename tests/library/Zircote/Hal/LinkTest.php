<?php
/**
 * Zircote_Hal_Link test case.
 */
class Zircote_Hal_LinkTest extends PHPUnit_Framework_TestCase
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

        $parentRes = new Zircote_Hal_Resource('/orders');
        $parentRes->setLink(
        new Zircote_Hal_Link('/orders?page=2', 'next')
        )->setLink(
        new Zircote_Hal_Link('/orders?id={order_id}', 'search')
        );
        $data = array(
        'total' => '30.00','currency' => 'USD',
        'status' => 'shipped','placed' => '2011-01-16',
        );
        $embedded1 = new Zircote_Hal_Resource('/orders/123');
        $embedded1->setData($data)
        ->setLink(
        new Zircote_Hal_Link(
        '/customer/bob','customer','Bob Jones <bob@jones.com>'
        )
        );
        $basketItems = array(
        array('sku' => 'ABC123','quantity' => 2,'price' => '9.50'),
        array('sku' => 'GFZ111','quantity' => 1,'price' => '11.00')
        );
        $basket = new Zircote_Hal_Resource('/orders/123/basket');
        $basket->setData('items', $basketItems);
        $embedded1->setEmbedded('basket', $basket, true);
        //////////////////////////////////////////////
        $data = array(
        'total' => '20.00','currency' => 'USD',
        'status' => 'processing','placed' => '2011-01-16',
        );
        $embedded2 = new Zircote_Hal_Resource('/orders/124');
        $embedded2->setData($data)
        ->setLink(
        new Zircote_Hal_Link(
        '/customer/jen','customer','Jen Harris <jen@internet.com>'
        )
        );
        /////////////////////////////////////
        $basketItems = array(
        array('sku' => 'KLM222','quantity' => 1,'price' => "9.00"),
        array('sku' => 'HHI50','quantity' => 1,'price' => "11.00")
        );
        $basket2 = new Zircote_Hal_Resource('/orders/124/basket');
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
        $parentRes = new Zircote_Hal_Resource('/orders');
        $parentRes->setLink(
            new Zircote_Hal_Link('/orders?page=2', 'next')
        )->setLink(
            new Zircote_Hal_Link('/orders?id={order_id}', 'search')
        );
        $data = array(
            'total' => '30.00','currency' => 'USD',
            'status' => 'shipped','placed' => '2011-01-16',
        );
        $embedded1 = new Zircote_Hal_Resource('/orders/123');
        $embedded1->setData($data)
            ->setLink(
                new Zircote_Hal_Link(
                    '/customer/bob','customer','Bob Jones <bob@jones.com>'
                )
            );
        $basketItems = array(
            array('sku' => 'ABC123','quantity' => 2,'price' => '9.50'),
            array('sku' => 'GFZ111','quantity' => 1,'price' => '11.00')
        );
        $basket = new Zircote_Hal_Resource('/orders/123/basket');
        $basket->setData('items', $basketItems);
        $embedded1->setEmbedded('basket', $basket, true);
        //////////////////////////////////////////////
        $data = array(
            'total' => '20.00','currency' => 'USD',
            'status' => 'processing','placed' => '2011-01-16',
        );
        $embedded2 = new Zircote_Hal_Resource('/orders/124');
        $embedded2->setData($data)
        ->setLink(
            new Zircote_Hal_Link(
                '/customer/jen','customer','Jen Harris <jen@internet.com>'
            )
        );
        /////////////////////////////////////
        $basketItems = array(
            array('sku' => 'KLM222','quantity' => 1,'price' => "9.00"),
            array('sku' => 'HHI50','quantity' => 1,'price' => "11.00")
        );
        $basket2 = new Zircote_Hal_Resource('/orders/124/basket');
        $basket2->setData('items', $basketItems);
        $embedded2->setEmbedded('basket', $basket2, true);

        $parentRes
        ->setEmbedded('order', $embedded1)
        ->setEmbedded('order', $embedded2);
        $actual = Zend_Json::decode($parentRes);
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
        $expected = Zend_Json::decode($JSON);
        $this->assertEquals($expected, $actual);
    }
}

