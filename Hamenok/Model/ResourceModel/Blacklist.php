<?php

declare(strict_types=1);

namespace Amasty\Hamenok\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Blacklist extends AbstractDb
{
    public const MAIN_TABLE = 'amasty_hamenok_blacklist';

    protected function _construct()
    {
        $this->_init(
            self::MAIN_TABLE,
            'blacklist_id'
        );
    }
}