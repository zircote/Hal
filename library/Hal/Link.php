<?php
/**
 *
 * @category Hal
 * @package Hal
 */
namespace Hal;
use SimpleXMLElement,
    Hal\Resource;
/**
 *
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
 */
class Link extends AbstractHal
{
    /**
     * For labeling the destination of a link with a human-readable identifier.
     * @var string
     */
    protected $_title;
    /**
     * For identifying how the target URI relates to the 'Subject Resource'.
     * The Subject Resource is the closest parent Resource element.
     *
     * @var string
     */
    protected $_rel;
    /**
     * - <b>As a resource:</b>
     *     Content embedded within a Resource element MAY be a full, partial, summary,
     *     or incorrect representation of the content available at the target URI.
     *     Applications which use HAL MAY clarify the integrity of specific embedded
     *     content via the description of the relevant @rel value.
     * - <b>As a link:</b>
     *     This attribute MAY contain a URI template. Whether or not this is the case
     *     SHOULD be indicated to clients by the @rel value.
     *
     * @var string
     */
    protected $_href;
    /**
     *
     * For distinguishing between Resource and Link elements that share the
     * same @rel value. The @name attribute SHOULD NOT be used exclusively
     * for identifying elements within a HAL representation, it is intended
     * only as a 'secondary key' to a given @rel value.
     * @var string
     */
    protected $_name;
    /**
     * For indicating what the language of the result of dereferencing the
     * link should be.
     * @var string
     */
    protected $_hreflang;
    /**
     *
     * @param string $href
     * @param string $rel
     * @param string $title
     * @param string $name
     * @param string $hreflang
     */
    public function __construct($href, $rel = 'self', $title = null, $name = null, $hreflang = null)
    {
        $this->setHref($href)
            ->setRel($rel)
            ->setName($name)
            ->setTitle($title)
            ->setHreflang($hreflang);
    }
    /**
     * @return string
     */
    public function getRel ()
    {
        return $this->_rel;
    }

    /**
     * @return string
     */
    public function getHref ()
    {
        return $this->_href;
    }
    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    /**
     * @return string
     */
    public function getTitle ()
    {
        return $this->_title;
    }

    /**
     * @return string
     */
    public function getHreflang ()
    {
        return $this->_hreflang;
    }
    /**
     * @param string $rel
     * @return Link
     */
    public function setRel ($rel)
    {
        $this->_rel = $rel;
        return $this;
    }
    /**
     * @param string $href
     * @return Link
     */
    public function setHref ($href)
    {
        $this->_href = $href;
        return $this;
    }
    /**
     *
     * @param string $name
     * @return Link
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }
    /**
     * @param string $title
     * @return Link
     */
    public function setTitle ($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * @param string $hreflang
     * @return Link
     */
    public function setHreflang ($hreflang)
    {
        $this->_hreflang = $hreflang;
        return $this;
    }
    /**
     *
     * @return array
     */
    public function toArray()
    {
        $link = array('href' => $this->getHref());
        if($this->getTitle()){
            $link['title'] = $this->getTitle();
        }
        if($this->getTitle()){
            $link['title'] = $this->getTitle();
        }
        if($this->getName()){
            $link['name'] = $this->getName();
        }
        if($this->getHreflang()){
            $link['hreflang'] = $this->getHreflang();
        }
        return $link;
    }
    /**
     * @return SimpleXMLElement
     */
    public function addLink(SimpleXMLElement $xml)
    {
        $this->_xml = new SimpleXMLElement('<link/>');
        $this->setXMLAttributes($this);
        return $this->_xml;
    }
}