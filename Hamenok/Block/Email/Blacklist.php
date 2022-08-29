<?php

declare(strict_types=1);

namespace Amasty\Hamenok\Block\Email;

use Magento\Framework\View\Element\Template;
use Amasty\Hamenok\Model\Blacklist as BlacklistModel;
use Amasty\Hamenok\Model\BlacklistRepository;
use Amasty\Hamenok\Model\ResourceModel\Blacklist\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;

class Blacklist extends Template
{
    /**
     * @var CollectionFactory
     */
    private $blacklistCollectionFactory;

    /**
     * @var BlacklistRepository
     */
    private $blacklistRepository;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    public function __construct(
        Template\Context $context,
        CollectionFactory $blacklistCollectionFactory,
        BlacklistRepository $blacklistRepository,
        ResultFactory $resultFactory,
        array $data=[]
    ) {
        $this->blacklistRepository = $blacklistRepository;
        $this->resultFactory = $resultFactory;
        $this->blacklistCollectionFactory = $blacklistCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getBlacklist(): BlacklistModel
    {
        $blacklistId = $this->getData('blacklist_id');

        return $this->blacklistRepository->getBlacklistById((int)$blacklistId);
    }

    public function getCollectionBlacklist()
    {
        return $this->blacklistCollectionFactory->create();
    }
}