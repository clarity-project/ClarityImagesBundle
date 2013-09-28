<?php

namespace Clarity\ImagesBundle\Form\Strategy;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
interface StrategyInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return mixed
     */
    public function upload(UploadedFile $file);
}