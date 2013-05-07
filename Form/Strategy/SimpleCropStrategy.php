<?php

namespace Clarity\ImagesBundle\Form\Strategy;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class SimpleCropStrategy implements CropStrategyInterface
{   
    /**
     * @var string
     */
    protected $root;

    public function __construct($root)
    {
        $this->root;
    }

    /**
     * {@inheritDoc}
     */
    public function crop(array $data)
    {
        die(var_dump($data, 'asdfasdf'));
    }
}