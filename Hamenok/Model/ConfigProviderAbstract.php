<?php

namespace Amasty\Hamenok\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

abstract class ConfigProviderAbstract
{
    /**
     * @var string
     */
    protected $pathPrefix;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    abstract protected function getValue(string $path);
    abstract protected function isSetFlag(string $path): bool;
}