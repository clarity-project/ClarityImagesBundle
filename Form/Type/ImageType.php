<?php

namespace Clarity\ImagesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class ImageType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'clarity_image';
    }
}