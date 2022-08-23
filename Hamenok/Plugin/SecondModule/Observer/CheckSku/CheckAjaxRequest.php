<?php

declare(strict_types=1);

namespace Amasty\Hamenok\Plugin\SecondModule\Observer\CheckSku;
use Amasty\SecondModule\Observer\CheckSku;
use Magento\Framework\App\RequestInterface;

class CheckAjaxRequest
{
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        RequestInterface $request
    ){
        $this->request = $request;
    }

    public function aroundExecute(CheckSku $subject, callable $proceed, $observer)
    {
        if ($this->request->isAjax()) {
            return $proceed($observer);
        }
    }
}