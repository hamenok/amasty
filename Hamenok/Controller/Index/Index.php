<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Amasty\Hamenok\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Catalog index page controller.
 */
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
