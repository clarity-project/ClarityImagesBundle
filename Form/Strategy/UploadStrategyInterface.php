<?php

namespace Clarity\ImagesBundle\Form\Strategy;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
interface UploadStrategyInterface
{
    /**
     * @param  \Symfony\Component\HttpFoundation\File\UploadedFile\UploadedFile $file
     * @return object|string
     */
    public function upload(UploadedFile $file);
}