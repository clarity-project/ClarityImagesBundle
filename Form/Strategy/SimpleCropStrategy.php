<?php

namespace Clarity\ImagesBundle\Form\Strategy;

use Clarity\ImagesBundle\Image\Manipulator;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class SimpleCropStrategy implements CropStrategyInterface
{   
    /**
     * @var string
     */
    protected $root;

    /**
     * @var \Clarity\ImagesBundle\Image\Manipulator
     */
    protected $manipulator;

    /**
     * @param \Clarity\ImagesBundle\Image\Manipulator $manipulator
     * @param string $root 
     */
    public function __construct(Manipulator $manipulator, $root)
    {
        $this->manipulator = $manipulator;
        $this->root = $root;
    }

    /**
     * {@inheritDoc}
     */
    public function crop(array $data)
    {
        $image = $this->manipulator->getImage($this->root.$data['uri']);
        // validate sizes
        $realSize = array(
            'width' => $image->getSize()->getWidth(),
            'height' => $image->getSize()->getHeight(),
        );
        $cropSize = explode('_', $data['sizes']);
        $cropSize = array(
            'width' => (int) $cropSize[0],
            'height' => (int) $cropSize[1],
        );

        if (!$this->isValidSizes($realSize, $cropSize)) {
            return false;
        }
        
        // get coord data
        $coords = array(
            'w' => $data['w'],
            'h' => $data['h'],
            'x' => $data['x'],
            'y' => $data['y'],
        );
        // make crop
        if (!$this->manipulator->crop($image, $coords, $this->root.$data['uri'])) {
            return false;
        }

        // resizing
        if (!$this->manipulator->resize($this->root.$data['uri'], $cropSize['width'], $cropSize['height'], $this->root.$data['uri'])) {
            return false;
        }

        return $data['uri'];
    }

    /**
     * @param  array   $realSize 
     * @param  array   $cropSize 
     * @return boolean
     */
    protected function isValidSizes(array $realSize, array $cropSize)
    {
        if ($cropSize['width'] > $realSize['width'] || $cropSize['height'] > $realSize['height']) {
            return false;
        }

        return true;
    }
}