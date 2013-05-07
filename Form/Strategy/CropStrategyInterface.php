<?php

namespace Clarity\ImagesBundle\Form\Strategy;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
interface CropStrategyInterface
{   
    /**
     * @param  array  $data
     * @return object|string
     */
    public function crop(array $data);
}