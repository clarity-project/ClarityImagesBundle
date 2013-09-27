<?php

namespace Clarity\ImagesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Clarity\ImagesBundle\DependencyInjection\Compiler\FormPass;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class ClarityImagesBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new FormPass());
    }
}
