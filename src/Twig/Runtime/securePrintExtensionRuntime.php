<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;
use Twig\Markup;

class securePrintExtensionRuntime implements RuntimeExtensionInterface
{

    public function securePrint($value): string
    {
        return new Markup($value, 'UTF-8');
    }
}
