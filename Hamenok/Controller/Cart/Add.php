<?php

namespace Amasty\Hamenok\Controller\Cart;

use Amasty\Hamenok\Model\ConfigProvider;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class Add extends Action
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    Public function __construct(
        Session $checkoutSession,
        ProductRepositoryInterface $productRepository,
        ConfigProvider $configProvider,
        Context $context
    ){
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->configProvider = $configProvider;
    }

    public function execute()
    {
        $redirect = $this->resultRedirectFactory->create();
        if (!$this->configProvider->getIsEnabledModule()) {
            die('Access is denied. Module is disabled');
        } else {
            if (!$this->configProvider->getIsEnabledQty()) {
                $this->messageManager->addWarningMessage(__('Поле "Количество" отключено! Товар не может быть добавлен'));
                return $redirect->setPath('hamenok');
            }

            $skuProduct = $this->getRequest()->getParam('sku');
            $qtyProduct = $this->getRequest()->getParam('qty');

            try {
                if ($skuProduct == null || $qtyProduct == null) {
                    $this->messageManager->addWarningMessage(__('Товар не добавлен! Не все поля заполнены!'));
                    return $redirect->setPath('hamenok');
                } else {
                    $product = $this->productRepository->get($skuProduct);
                }
            } catch (NoSuchEntityException $error) {
                $this->messageManager->addNoticeMessage(__('Товар не найден!'));
                return $redirect->setPath('hamenok');
            }

            $stockQty = $product->getExtensionAttributes()->getStockItem()->getQty();

            if ($product->getTypeId()!== 'simple') {
                $this->messageManager->addErrorMessage(__('Товар не добавлен! Заказывать можно только "Simple product"'));
            } elseif ($qtyProduct > $stockQty) {
                $this->messageManager->addErrorMessage(__('Товар не добавлен! Нет необходимого количества на складе!'));
            } else {
                $quote = $this->checkoutSession->getQuote();
                if (!$quote->getId()) {
                    $quote->save();
                }

                $quote->addProduct($product, $qtyProduct);
                $quote->save();
                $this->messageManager->addSuccessMessage(__('Товар добавлен в корзину!'));
            }
            return $redirect->setPath('hamenok');
        }
    }
}