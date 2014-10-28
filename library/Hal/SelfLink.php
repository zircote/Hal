<?php
namespace Hal;

use Hal\Resource;

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
class SelfLink extends Link
{
    const rel = 'self';

    /**
     *
     * @param string $href
     * @param string $title
     * @param string $name
     * @param string $hreflang
     * @param boolean $templated
     */
    public function __construct($href, $title = null, $name = null, $hreflang = null, $templated = false)
    {
        $this->setHref($href)
            ->setRel(self::rel)
            ->setName($name)
            ->setTitle($title)
            ->setHreflang($hreflang)
            ->setTemplated($templated);
    }
}
