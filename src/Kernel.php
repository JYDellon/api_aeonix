<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureBundles(): iterable
    {
        $bundles = [
            // Autres bundles ici
        ];

        if ($this->getEnvironment() === 'dev') {
        }

        return $bundles;
    }
}