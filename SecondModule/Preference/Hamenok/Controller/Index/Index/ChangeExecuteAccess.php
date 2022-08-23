<?php

declare(strict_types=1);

namespace Amasty\SecondModule\Preference\Hamenok\Controller\Index\Index;

use Amasty\Hamenok\Controller\Index\Index;
use Amasty\Hamenok\Model\ConfigProvider;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\ResultFactory;

class ChangeExecuteAccess extends Index
{
    /**
     * @var Session
     */
    private $session;

    public function __construct(
        ResultFactory $resultFactory,
        ConfigProvider $configProvider,
        Session $session
    )
    {
        parent::__construct($resultFactory, $configProvider);
        $this->session = $session;
    }

    public function execute()
        {
            if ($this->session->isLoggedIn()){
                return parent::execute();
            } else {
                die('Access denied.');
            }

        }
}