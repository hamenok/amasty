<?php

declare(strict_types=1);

namespace Amasty\SecondModule\Observer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CheckSku implements ObserverInterface
{
    public const FOR_SKU = 'test_config_2/general/for_sku';
    public const PROMO_SKU = 'test_config_2/general/promo_sku';
    public const QTY_PROMO_PRODUCT = 1;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        Session $checkoutSession,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(Observer $observer)
    {
        $promoSku = $this->scopeConfig->getValue(self::PROMO_SKU);
        $forSku = $this->scopeConfig->getValue(self::FOR_SKU);
        $currentSku = $observer->getSku();

        if (str_contains($forSku, $currentSku)){
            $product = $this->productRepository->get($promoSku);
            $quote = $this->checkoutSession->getQuote();

            if (!$quote->getId()) {
                $quote->save();
            }

            $quote->addProduct($product, self::QTY_PROMO_PRODUCT);
            $quote->save();
        }
    }
}