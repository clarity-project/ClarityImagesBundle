<?php

namespace Clarity\ImagesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Add a new twig.form.resources
 *
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class FormPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('twig.form.resources');

        foreach (array('image') as $template) {
            $resources[] = 'ClarityImagesBundle:Form:' . $template . '.html.twig';
        }

        $container->setParameter('twig.form.resources', $resources);
    }
}