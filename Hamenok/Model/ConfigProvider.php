<?php

namespace Amasty\Hamenok\Model;

class ConfigProvider extends ConfigProviderAbstract
{
    protected $pathPrefix = 'test_config/';

    const MAIN_GROUP = 'general/';
    const IS_ENABLE_MODULE = 'enabled';
    const IS_ENABLE_QTY = 'visible_qty';
    const DEFAULT_QTY_VALUE = 'default_qty';
    const WELCOME_MESSAGE = 'welcome_text';
    const EMAIL = 'email';
    const EMAIL_TEMPLATE = 'email_template';

    protected function getValue(string $path)
    {
        return $this->scopeConfig->getValue($this->pathPrefix . $path);
    }

    protected function isSetFlag(string $path): bool
    {
        return (bool)$this->scopeConfig->isSetFlag($this->pathPrefix . $path);
    }

    public function getIsEnabledModule(): bool
    {
        return (bool)$this->isSetFlag(self::MAIN_GROUP . self::IS_ENABLE_MODULE);
    }

    public function getIsEnabledQty(): bool
    {
        return (bool)$this->isSetFlag(self::MAIN_GROUP . self::IS_ENABLE_QTY);
    }

    public function getWelcomeMessage(): string
    {
        return (string)$this->getValue(self::MAIN_GROUP . self::WELCOME_MESSAGE);
    }

    public function getQty(): int
    {
        return (int)$this->getValue(self::MAIN_GROUP . self::DEFAULT_QTY_VALUE);
    }

    public function getEmail(): string
    {
        return (string)$this->getValue(self::MAIN_GROUP . self::EMAIL);
    }

    public function getEmailTemplate()
    {
        return $this->getValue(self::MAIN_GROUP . self::EMAIL_TEMPLATE);
    }
}