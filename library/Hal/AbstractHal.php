<?php
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
 *
 * @package
 * @subpackage
 *
 *
 */
abstract class Hal_AbstractHal
{
	/**
	 *
	 * @var SimpleXMLElement
	 */
	protected $_xml;

	/**
	 * @param SimpleXMLElement $xml
	 * @param Hal_Link $link
	 * @return Hal_AbstractHal
	 */
	public function setXMLAttributes(SimpleXMLElement $xml, Hal_Link $link)
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
