<?php

namespace Amasty\Hamenok\Model\ResourceModel\Blacklist;

use Amasty\Hamenok\Model\Blacklist as BlacklistModel;
use Amasty\Hamenok\Model\ResourceModel\Blacklist;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'blacklist_id';
    protected $_eventPrefix = 'amasty_hamenok_blacklist_collection';
    protected $_eventObject = 'blacklist_collection';

    protected function _construct()
    {
        $this->_init(BlacklistModel::class, Blacklist::class);
    }
}