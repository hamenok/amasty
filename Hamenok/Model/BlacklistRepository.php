<?php

declare(strict_types=1);

namespace Amasty\Hamenok\Model;

use Amasty\Hamenok\Model\ResourceModel\Blacklist as BlacklistResource;

class BlacklistRepository
{
    /**
     * @var BlacklistResource
     */
    private $blacklistResource;

    /**
     * @var BlacklistFactory
     */
    private $blacklistFactory;

    public function __construct(
        BlacklistResource $blacklistResource,
        BlacklistFactory $blacklistFactory
    ) {
        $this->blacklistResource = $blacklistResource;
        $this->blacklistFactory = $blacklistFactory;
    }

    public function getBlacklistById(int $blacklistId): Blacklist
    {
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load($blacklist, $blacklistId);

        return $blacklist;
    }

    public function save(Blacklist $blacklist): Blacklist
    {
        $this->blacklistResource->save($blacklist);
        return $blacklist;
    }
}