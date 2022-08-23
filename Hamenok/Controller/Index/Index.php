<?php

declare(strict_types=1);

namespace Amasty\Hamenok\Controller\Index;

use Magento\Framework\App\ActionInterface;
use Amasty\Hamenok\Model\ConfigProvider;
use Magento\Framework\Controller\ResultFactory;

class Index implements ActionInterface
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    public function __construct(
        ResultFactory $resultFactory,
        ConfigProvider $configProvider
    )
    {
        $this->resultFactory = $resultFactory;
        $this->configProvider = $configProvider;
    }

    public function execute()
    {
        if ($this->configProvider->getIsEnabledModule()) {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        } else {
            die('Sorry, module is disable');
        }
    }
}
