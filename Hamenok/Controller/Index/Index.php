<?php

namespace Amasty\Hamenok\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;

class Index extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
{
    /**
     * Index action
     *
     * @return string
     */
    public function execute()
    {
        die('Привет Magento. Привет Amasty. Я готов тебя покорить!');
    }
}
