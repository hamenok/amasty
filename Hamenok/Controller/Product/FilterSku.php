<?php

namespace Amasty\Hamenok\Controller\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class FilterSku extends Action
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        Context $context,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {
        $skuProduct = $this->getRequest()->getParam('sku');
        $productCollection = $this->collectionFactory->create();
        $resultJson = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
        try {
            $productCollection->addAttributeToFilter('sku', ['like' => $skuProduct . '%'])->setPageSize(6);
            $data = [];
            foreach ($productCollection as $product) {
                $data[] = $this->productRepository->get($product->getSku())->getData();
            }
            $resultJson->setData($data);
            return $resultJson;
        } catch (NoSuchEntityException $error) {
            die($error->getMessage());
        }
    }
}