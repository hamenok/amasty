<?php

declare(strict_types=1);

namespace Amasty\Hamenok\Controller\Email;

use Amasty\Hamenok\Model\SendEmail as SendEmailModel;

class SendEmail
{
    /**
     * @var SendEmailModel
     */
    private $sendEmail;

    public function __construct(
        SendEmailModel $sendEmail
    ){
      $this->sendEmail = $sendEmail;
    }

    public function execute()
    {
        $this->sendEmail->Send();
    }
}