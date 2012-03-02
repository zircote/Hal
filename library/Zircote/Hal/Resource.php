<?php
/**
 *
 * @author zircote
 * @package Zircote_Hal
 *
 */
class Zircote_Hal_Resource extends Zircote_Hal_AbstractHal
{
    /**
     * Internal storage of `Zircote_Hal_Link` objects
     * @var array
     */
    protected $_links = array();
    /**
     * Internal storage of primitive types
     * @var array
     */
    protected $_data = array();
    /**
     * Internal storage of `Zircote_Hal_Resource` objects
     * @var array
     */
    protected $_embedded = array();
    /**
     *
     * @param string $href
     * @param string $name
     */
    public function __construct($href, $rel = null, $title = null, $name = null, $hreflang = null)
    {
        $this->setLink(
            new Zircote_Hal_Link($href, 'self', $title, $name, $hreflang)
        );
    }
    /**
     * @return Zircote_Hal_Link
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
     *
     * @param Zircote_Hal_Link $link
     * @return Zircote_Hal_Resource
     */
    public function setLink(Zircote_Hal_Link $link)
    {
        $this->_links[$link->getRel()] = $link;
        return $this;
    }
    /**
     *
     * @param array $data
     * @return Zircote_Hal_Resource
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
     * @param Zircote_Hal_Resource $resource
     * @return Zircote_Hal_Resource
     */
    public function setEmbedded($rel,Zircote_Hal_Resource $resource, $singular = false)
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
            $data['_links'][$rel] = $link->toArray();
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
        /* @var $embed Zircote_Hal_Resource */
        foreach ($embedded as $rel => $embed) {
            if($embed instanceof Zircote_Hal_Resource){
                $rel = is_numeric($rel) ? $_rel : $rel;
                $this->_getEmbRes($embed)->addAttribute('rel', $rel);
            } else {
                $this->_getEmbedded($embed,$rel);
            }
        }
    }
    protected function _getEmbRes(Zircote_Hal_Resource $embed)
    {
        $resource = $this->_xml->addChild('resource');
        return $embed->getXML($resource);
    }
    /**
     *
     * @param SimpleXMLElement $xml
     * @return Zircote_Hal_Resource
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
    protected function _addData(SimpleXMLElement $xml, array $data, $key = null)
    {
        if(null !== $key && !is_numeric($key)){
            $node = $xml->addChild($key);
            $this->_addData($node, $data);
        } else {
            foreach ($data as $_key => $value) {
                if(!is_numeric($_key) && is_array($value)){
                    foreach ($value as $v) {
                        $c = $xml->addChild($_key)->addChild(rtrim($_key, 's'));
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
                } else {
                    $this->_addData($xml, $value, $_key);
                }
            }
        }
    }
    /**
     *
     * @param Zircote_Hal_Link $link
     */
    protected function _addLinks(Zircote_Hal_Link $link)
    {
        if($link->getRel() != 'self' && !is_numeric($link->getRel())){
            $this->_addLink($link);
        }
    }
    /**
     *
     * @param Zircote_Hal_Link $link
     * @return Zircote_Hal_Resource
     */
    protected function _addLink(Zircote_Hal_Link $link)
    {
        $this->setXMLAttributes($this->_xml->addChild('link'), $link);
        return $this;
    }
}
