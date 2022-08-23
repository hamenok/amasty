<?php

namespace Amasty\Hamenok\Controller\Product;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class FilterSku extends Action
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {
        $skuProduct = $this->getRequest()->getParam('sku');
        $productCollection = $this->collectionFactory->create();
        $resultJson = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
        try {
            $productCollection->addAttributeToFilter('sku', ['like' => $skuProduct . '%'])
                            ->addAttributeToSelect('name')
                            ->setPageSize(6);
            $data = [];
            foreach ($productCollection as $product) {
                $data[] = $product->getData();
            }
            $resultJson->setData($data);
            return $resultJson;
        } catch (NoSuchEntityException $error) {
            die($error->getMessage());
        }
    }
}