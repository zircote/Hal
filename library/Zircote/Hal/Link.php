<?php
/**
 *
 * @author zircote
 * @package Zircote_Hal
 */
class Zircote_Hal_Link extends Zircote_Hal_AbstractHal
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
     * @return Zircote_Hal_Link
     */
    public function setRel ($rel)
    {
        $this->_rel = $rel;
        return $this;
    }
    /**
     * @param string $href
     * @return Zircote_Hal_Link
     */
    public function setHref ($href)
    {
        $this->_href = $href;
        return $this;
    }
    /**
     *
     * @param string $name
     * @return Zircote_Hal_Link
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }
	/**
     * @param string $title
     * @return Zircote_Hal_Link
     */
    public function setTitle ($title)
    {
        $this->_title = $title;
        return $this;
    }

	/**
     * @param string $hreflang
     * @return Zircote_Hal_Link
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
        if($this->getRel()){
//             $link['rel'] = $this->getRel();
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