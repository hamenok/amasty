<?php

declare(strict_types=1);

namespace Amasty\Hamenok\Model;

use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Area;
use Magento\Store\Model\StoreManagerInterface;

class SendEmail
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var BlacklistRepository
     */
    private $blacklistRepository;

    /**
     * @var FactoryInterface
     */
    private $templateFactory;

    public function __construct(
        ConfigProvider $configProvider,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        BlacklistRepository $blacklistRepository,
        FactoryInterface $templateFactory
    ){
        $this->configProvider = $configProvider;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->blacklistRepository = $blacklistRepository;
        $this->templateFactory = $templateFactory;
    }
    public function Send()
    {
        $blacklist = $this->blacklistRepository->getBlacklistById(1);

        $vars = [
            'blacklist_id'=>$blacklist->getId(),
            'sku'=>$blacklist->getSku(),
            'qty'=>$blacklist->getQty(),
        ];

        $sender = [
            'name'=>'Hamenok',
            'email'=>'amasty_hamenok@gmail.com',
        ];

        $this->transportBuilder->setTemplateIdentifier(
            $this->configProvider->getEmailTemplate()
        )->setTemplateOptions(
            [
                'area' => Area::AREA_FRONTEND,
                'store' => $this->storeManager->getStore()->getId(),
            ]
        )->setTemplateVars(
            $vars
        )->setFromByScope(
            $sender
        )->addTo($this->configProvider->getEmail());

        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();

        $template = $this->templateFactory->get($this->configProvider->getEmailTemplate());
        $template->setOptions([
            'area' => Area::AREA_FRONTEND,
            'store' => $this->storeManager->getStore()->getId(),
        ]);
        $body = $template->processTemplate();

        $blacklist->setEmailBody($body);

        $this->blacklistRepository->save($blacklist);
    }
}