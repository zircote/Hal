<?php

abstract class Zircote_Hal_AbstractHal
{
    /**
     *
     * @var SimpleXMLElement
     */
    protected $_xml;
    /**
     * @param SimpleXMLElement $xml
     * @param Zircote_Hal_Link $link
     * @return Zircote_Hal_AbstractHal
     */
    public function setXMLAttributes(SimpleXMLElement $xml, Zircote_Hal_Link $link)
    {
        $xml->addAttribute('href', $link->getHref());
        if($link->getRel() && $link->getRel() !== 'self'){
            $xml->addAttribute('rel', $link->getRel());
        }
        if($link->getName()){
            $xml->addAttribute('name', $link->getName());
        }
        if($link->getTitle()){
            $xml->addAttribute('title', $link->getTitle());
        }
        if($link->getHreflang()){
            $xml->addAttribute('hreflang', $link->getHreflang());
        }
        return $this;
    }
}