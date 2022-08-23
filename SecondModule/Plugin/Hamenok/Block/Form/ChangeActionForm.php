<?php

declare(strict_types=1);

namespace Amasty\SecondModule\Plugin\Hamenok\Block\Form;

use Amasty\Hamenok\Block\Form\Form;

class ChangeActionForm
{
    public const ACTION_FORM = 'checkout/cart/add';

    public function afterGetFormAction(Form $subject, string $result): string
    {
        return self::ACTION_FORM;
    }
}