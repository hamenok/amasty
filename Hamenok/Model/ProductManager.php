<?php

declare(strict_types=1);

namespace Amasty\Hamenok\Model;

use Amasty\Hamenok\Model\BlacklistFactory;
use Amasty\Hamenok\Model\ResourceModel\Blacklist as BlacklistResource;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class ProductManager
{
    public const EVENT_NAME = 'amasty_hamenok_product_added_to_cart';
    public const COLUMN_SKU = 'sku';

    /**
     * @var BlacklistFactory
     */
    private $blacklistFactory;

    /**
     * @var BlacklistResource
     */
    private $blacklistResource;

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
        BlacklistFactory $blacklistFactory,
        BlacklistResource $blacklistResource,
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
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistResource = $blacklistResource;
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

            $blacklist = $this->blacklistFactory->create();
            $this->blacklistResource->load(
                $blacklist,
                $skuProduct,
                self::COLUMN_SKU
            );

            $blacklistQty = $blacklist->getQty();

            if (count($blacklist->getData()) > 0){
                $item = $quote->getItemByProduct($product);

                if ($item)
                {
                    if ($item->getSku() == $skuProduct) {
                        $summQty = $item->getQty() + $qtyProduct;
                        if ($summQty > $blacklistQty) {
                            $summQty = $blacklist - $item->getQty();
                        }
                    } else {
                        $summQty = $qtyProduct;
                    }

                    if ($summQty > $blacklistQty){
                        $this->addProductToCart($quote, $product, $blacklistQty,
                                            'Товар был добавлен в количестве: ' . $blacklistQty . ' шт.');
                    } else {
                        $this->addProductToCart($quote, $product, $summQty,
                                            'Товар был добавлен в количестве: ' . $summQty . ' шт.');
                    }

                } else {
                    if ($qtyProduct > $blacklistQty){
                        $this->addProductToCart($quote, $product, $blacklistQty,
                                            'Товар был добавлен в количестве: ' . $blacklistQty . ' шт.');
                    } else {
                        $this->addProductToCart($quote, $product, $qtyProduct, 'Товар добавлен в корзину!' );
                    }
                }
            } else {
                $this->addProductToCart($quote, $product, $qtyProduct, 'Товар добавлен в корзину!' );
            }

            $this->eventManager->dispatch(
                self::EVENT_NAME,
                [
                    'sku' => $skuProduct
                ]
            );
        }
    }

    private function addProductToCart($quote, $product, $qty, string $msg)
    {
        $quote->addProduct($product, $qty);
        $quote->save();
        $this->messageManager->addSuccessMessage(__($msg));
    }
}


