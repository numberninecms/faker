<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\FakerBundle;

use NumberNine\Model\Bundle\Bundle;

class NumberNineFakerBundle extends Bundle
{
    protected function getAlias(): string
    {
        return 'numbernine_faker';
    }
}