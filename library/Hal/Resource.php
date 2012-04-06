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
 * @package Hal
 *
 */
class Hal_Resource extends Hal_AbstractHal
{
    /**
     * Internal storage of `Hal_Link` objects
     * @var array
     */
    protected $_links = array();
    /**
     * Internal storage of primitive types
     * @var array
     */
    protected $_data = array();
    /**
     * Internal storage of `Hal_Resource` objects
     * @var array
     */
    protected $_embedded = array();
    /**
     *
     * @param string $href
     * @param string $name
     */
    public function __construct($href, array $data = array(), $title = null, $name = null, $hreflang = null)
    {
        $this->setLink(
            new Hal_Link($href, 'self', $title, $name, $hreflang)
        );
        $this->setData($data);
    }
    /**
     * @return Hal_Link
     */
    public function getSelf()
    {
        return $this->_links['self'];
    }
    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->_links;
    }
    /**
     * Add a link to the resource.
     *
     * Per the JSON-HAL specification, a link relation can reference a 
     * single link or an array of links. By default, two or more links with 
     * the same relation will be treated as an array of links. The $singular
     * flag will force links with the same relation to be overwritten.
     *
     * @param Hal_Link $link
     * @return Hal_Resource
     */
    public function setLink(Hal_Link $link, $singular=false)
    {
        $rel = $link->getRel();

        if (!isset($this->_links[$rel]) || $singular) {
            $this->_links[$rel] = $link;
        } else {
            if (!is_array($this->_links[$rel])) {
                $orig_link = $this->_links[$rel];
                $this->_links[$rel] = array($orig_link);
            }
            $this->_links[$rel][] = $link;
        }

        return $this;
    }
    /**
     *
     * @param array $data
     * @return Hal_Resource
     */
    public function setData($rel, $data = null)
    {
        if(is_array($rel) && null === $data){
            foreach ($rel as $k => $v) {
                $this->_data[$k] = $v;
            }
        } else {
            $this->_data[$rel] = $data;
        }
        return $this;
    }
    /**
     *
     * @param Hal_Resource $resource
     * @return Hal_Resource
     */
    public function setEmbedded($rel,Hal_Resource $resource, $singular = false)
    {
        if($singular){
            $this->_embedded[$rel] = $resource;
        } else {
            $this->_embedded[$rel][] = $resource;
        }
        return $this;
    }
    /**
     * @return array
     */
    public function toArray()
    {
        $data = array();
        foreach ($this->_links as $rel => $link) {
            $data['_links'][$rel] = $this->_recurseLinks($link);
        }
        foreach ($this->_data as $key => $value) {
            $data[$key] = $value;
        }
        foreach ($this->_embedded as $rel => $embed) {
            $data['_embedded'][$rel] = $this->_recurseEmbedded($embed);
        }
        return $data;
    }
    /**
     *
     * @param mixed $embeded
     */
    protected function _recurseEmbedded($embeded)
    {
        $result = array();
        if($embeded instanceof  self){
            $result = $embeded->toArray();
        } else {
            foreach ($embeded as $embed) {
                if($embed instanceof self){
                    $result[] = $embed->toArray();
                }
            }
        }
        return $result;
    }
    /**
     * 
     * @param mixed $links 
     */
    protected function _recurseLinks($links)
    {
        $result = array();
        if(!is_array($links)) {
            $result = $links->toArray();
        } else {
            foreach ($links as $link) {
                $result[] = $link->toArray();
            }
        }
        return $result;
    }
    /**
     *
     * @return string
     */
    public function __toJson()
    {
        return json_encode($this->toArray());
    }
    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->__toJson();
    }
    /**
     *
     * @return SimpleXMLElement
     */
    public function getXML($xml = null)
    {
        if(! $xml instanceof SimpleXMLElement){
            $xml = new SimpleXMLElement('<resource></resource>');
        }
        $this->_xml = $xml;
        $this->setXMLAttributes($this->_xml, $this->getSelf());
        foreach ($this->_links as $link) {
            $this->_addLinks($link);
        }
        $this->_addData($this->_xml, $this->_data);
        $this->_getEmbedded($this->_embedded);
        return $this->_xml;
    }
    /**
     *
     * @param mixed $embedded
     * @param string|null $_rel
     */
    protected function _getEmbedded($embedded, $_rel = null)
    {
        /* @var $embed Hal_Resource */
        foreach ($embedded as $rel => $embed) {
            if($embed instanceof Hal_Resource){
                $rel = is_numeric($rel) ? $_rel : $rel;
                $this->_getEmbRes($embed)->addAttribute('rel', $rel);
            } else {
                $this->_getEmbedded($embed,$rel);
            }
        }
    }
    protected function _getEmbRes(Hal_Resource $embed)
    {
        $resource = $this->_xml->addChild('resource');
        return $embed->getXML($resource);
    }
    /**
     *
     * @param SimpleXMLElement $xml
     * @return Hal_Resource
     */
    public function setXML(SimpleXMLElement $xml)
    {
        $this->_xml = $xml;
        return $this;
    }
    /**
     *
     * @param SimpleXMLElement $xml
     * @param array $data
     * @param string $key
     */
    protected function _addData(SimpleXMLElement $xml, $data, $key = null)
    {
        if(null !== $key && !is_numeric($key)){
            $node = $xml->addChild($key);
            $this->_addData($node, $data);
        } else {
            foreach ($data as $_key => $value) {
                if(!is_numeric($_key) && is_array($value)){
                    foreach ($value as $v) {
                        $c = $xml->addChild($_key)->addChild($_key);
                        foreach ($v as $name => $value) {
                            $c->addChild($name, $value);
                        }
                    }
                }
                elseif(!is_numeric($_key) && !is_array($value) && $value){
                    if($key && !is_numeric($key)){
                        $_k = $key;
                    } else {
                        $_k = $_key;
                    }
                        $xml->addChild($_k, $value);
                } elseif(is_array($value)){
                    $this->_addData($xml, $value, $_key);
                }
            }
        }
    }
    /**
     *
     * @param Hal_Link $link
     */
    protected function _addLinks(Hal_Link $link)
    {
        if($link->getRel() != 'self' && !is_numeric($link->getRel())){
            $this->_addLink($link);
        }
    }
    /**
     *
     * @param Hal_Link $link
     * @return Hal_Resource
     */
    protected function _addLink(Hal_Link $link)
    {
        $this->setXMLAttributes($this->_xml->addChild('link'), $link);
        return $this;
    }
}
