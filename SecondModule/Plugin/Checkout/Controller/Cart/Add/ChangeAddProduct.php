<?php

declare(strict_types=1);

namespace Amasty\SecondModule\Plugin\Checkout\Controller\Cart\Add;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Controller\Cart\Add;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class ChangeAddProduct
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        RequestInterface $request,
        ManagerInterface $messageManager
    ) {
        $this->productRepository = $productRepository;
        $this->request = $request;
        $this->messageManager = $messageManager;
    }
    public function beforeExecute(Add $subject)
    {
        $skuProduct = $this->request->getParam('sku');
        $qtyProduct = $this->request->getParam('qty');

        try {
            if ($skuProduct == null || $qtyProduct == null) {
                $this->messageManager->addWarningMessage(__('Товар не добавлен! Не все поля заполнены!'));
            } else {
                $product_id = $this->productRepository->get($skuProduct)->getId();
                $subject->getRequest()->setParams(['product' => $product_id, 'qty' => $qtyProduct]);
            }
        } catch(NoSuchEntityException $error) {
            $this->messageManager->addNoticeMessage(__('Товар не найден!'));
        }
    }
}