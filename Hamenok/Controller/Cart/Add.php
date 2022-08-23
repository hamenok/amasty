<?php

declare(strict_types=1);

namespace Amasty\Hamenok\Controller\Cart;

use Amasty\Hamenok\Model\ConfigProvider;
use Amasty\Hamenok\Model\ProductManager;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Add extends Action
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var ProductManager
     */
    private $productManager;

    public function __construct(
        ConfigProvider $configProvider,
        ProductManager $productManager,
        Context $context
    ) {
        parent::__construct($context);
        $this->configProvider = $configProvider;
        $this->productManager = $productManager;
    }

    public function execute()
    {
        if (!$this->configProvider->getIsEnabledModule()) {
            die('Access is denied. Module is disabled');
        } elseif (!$this->configProvider->getIsEnabledQty()) {
            $this->messageManager->addWarningMessage(__('Поле "Количество" отключено! Товар не может быть добавлен'));
        } else {
            $this->productManager->addProduct();
        }
        $redirect = $this->resultRedirectFactory->create();
        return $redirect->setPath('hamenok');
    }
}