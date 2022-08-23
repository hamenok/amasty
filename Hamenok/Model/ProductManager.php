<?php

declare(strict_types=1);

namespace Amasty\Hamenok\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class ProductManager
{
    public const EVENT_NAME = 'amasty_hamenok_product_added_to_cart';

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    Public function __construct(
        ProductRepositoryInterface $productRepository,
        Session $checkoutSession,
        ManagerInterface $messageManager,
        RequestInterface $request,
        EventManagerInterface $eventManager
    ){
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->eventManager = $eventManager;
    }

    public function addProduct()
    {
        $skuProduct = $this->request->getParam('sku');
        $qtyProduct = $this->request->getParam('qty');

        try {
            if ($skuProduct == null || $qtyProduct == null) {
                $this->messageManager->addWarningMessage(__('Товар не добавлен! Не все поля заполнены!'));
                return;
            } else {
                $product = $this->productRepository->get($skuProduct);
            }
        } catch (NoSuchEntityException $error) {
            $this->messageManager->addNoticeMessage(__('Товар не найден!'));
            return;
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

            $this->eventManager->dispatch(
                self::EVENT_NAME,
                [
                    'sku' => $skuProduct
                ]
            );
        }
    }
}


