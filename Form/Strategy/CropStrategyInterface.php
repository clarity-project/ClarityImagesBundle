<?php

namespace Clarity\ImagesBundle\Form\Strategy;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
interface CropStrategyInterface
{   
    /**
     * @param  array  $data
     * @param  array  $resize
     * @return object|string
     */
    public function handle(array $data, array $resize);
}