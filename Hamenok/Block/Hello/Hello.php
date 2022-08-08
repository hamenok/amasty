<?php


namespace Amasty\Hamenok\Block\Hello;

use Magento\Framework\View\Element\Template;

class Hello extends Template
{
    public function sayHiTo()
    {
        return 'Привет Magento. Привет Amasty. Я готов тебя покорить!';
    }
}