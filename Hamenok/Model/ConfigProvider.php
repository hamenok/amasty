<?php

namespace Amasty\Hamenok\Model;

class ConfigProvider extends ConfigProviderAbstract
{
    protected $pathPrefix = "test_config/";

    protected function getValue(string $path)
    {
        return $this->scopeConfig->getValue($this->pathPrefix . $path);
    }

    protected function isSetFlag(string $path): bool
    {
        return (bool) $this->scopeConfig->isSetFlag($this->pathPrefix . $path);
    }

    public function getIsEnabled(string $path): bool
    {
        return $this->isSetFlag($path);
    }

    public function getWelcomeMessage(string $path): string
    {
        return $this->getValue($path);
    }

    public function getQty(string $path): int
    {
        return $this->getValue($path);
    }
}