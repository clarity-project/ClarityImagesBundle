<?php

namespace Clarity\ImagesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class ImageUploadCropType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'clarity_image_upload';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'clarity_image_crop';
    }
}