<?php

abstract class Zircote_Hal_AbstractHal
{

    /**
     *
     * @var string
     */
    protected $_rel;
    /**
     *
     * @var string
     */
    protected $_href;
    /**
     *
     * @var string
     */
    protected $_name;
    /**
     *
     * @param string $href
     * @param string $rel
     * @param string $title
     * @param string $name
     */
    public function __construct($href, $rel = 'self', $name = null)
    {
        $this->setHref($href)
            ->setRel($rel)
            ->setName($name);
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
     * @param string $rel
     * @return Zircote_Json_Hal_Resource
     */
    public function setRel ($rel)
    {
        $this->_rel = $rel;
        return $this;
    }
    /**
     * @param string $href
     * @return Zircote_Json_Hal_Resource
     */
    public function setHref ($href)
    {
        $this->_href = $href;
        return $this;
    }
    /**
     *
     * @param string $name
     * @return Zircote_Hal_AbstractHal
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }
}