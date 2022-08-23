<?php

namespace Amasty\Hamenok\Block\Form;

use Amasty\Hamenok\Model\ConfigProvider;
use Magento\Framework\View\Element\Template;

class Form extends Template
{
    public const FORM_ACTION = 'hamenok/cart/add';

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    public function __construct(
        Template\Context $context,
        ConfigProvider $configProvider,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
    }

    public function getIsEnabledQty(): bool
    {
        return (bool)$this->configProvider->getIsEnabledQty();
    }

    public function getDefaultValueQty(): int
    {
        return (int)$this->configProvider->getQty();
    }

    public function getFormAction(): string
    {
        return self::FORM_ACTION;
    }
}