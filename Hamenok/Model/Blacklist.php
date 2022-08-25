<?php

declare(strict_types=1);

namespace Amasty\Hamenok\Model;

use Magento\Framework\Model\AbstractModel;
use Amasty\Hamenok\Model\ResourceModel\Blacklist as BlacklistResource;

class Blacklist extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(
            BlacklistResource::class
        );
    }
}