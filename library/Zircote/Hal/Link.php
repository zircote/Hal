<?php


class Zircote_Hal_Link extends Zircote_Hal_AbstractHal
{
    /**
     *
     * @var string
     */
    protected $_title;

    public function __construct($href, $rel = 'self', $title = null, $name = null)
    {
        $this->setTitle($title);
        parent::__construct($href, $rel, $name);
    }
    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }
    /**
     *
     * @param string $title
     * @return Zircote_Json_Hal_Resource
     */
    public function setTitle($title)
    {
        $this->_title = $title;
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
        return $link;
    }
}