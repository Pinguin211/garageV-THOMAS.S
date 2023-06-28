<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\securePrintExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class securePrintExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('securePrint', [securePrintExtensionRuntime::class, 'securePrint',  ['is_safe' => ['html']]]),
        ];
    }
}
