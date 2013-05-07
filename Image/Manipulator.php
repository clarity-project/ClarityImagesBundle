<?php

namespace Clarity\ImagesBundle\Image;

use Imagine\Gd\Imagine;
use Imagine\Gd\Image;
use Imagine\Image\Point;
use Imagine\Image\Box;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class Manipulator
{   
    /**
     * @param  string $image
     * @return \Imagine\Gd\Image
     */
    public function getImage($source)
    {
        $imagine = new Imagine();
        return $imagine->open($source);
    }

    /**
     * 
     * @param  mixed $image
     * @param  array $coords
     * @param  string $targetPath
     * @return boolean
     */
    public function crop($image, array $coords, $targetPath)
    {
        if (!$image instanceof Image) {
            $image = $this->getImage($image);
        }

        $image
            ->crop(
                new Point($coords['x'], $coords['y']), 
                new Box($coords['w'], $coords['h'])
            )
            ->save($targetPath);

        return true;
    }

    /**
     * 
     * @param  mixed $image
     * @param  int $width
     * @param  int $height
     * @param  string $targetPath
     * @return boolean
     */
    public function resize($image, $width = 0, $height = 0, $targetPath)
    {
        if ($width == 0 || $height == 0) {
            return false;
        }

        if (!$image instanceof Image) {
            $image = $this->getImage($image);
        }

        if ($width == 0) {
            $ratio = $image->getSize()->getWidth() / $image->getSize()->getHeight();
            $width = $ratio * $height;
        }

        if ($height == 0) {
            $ratio = $image->getSize()->getWidth() / $image->getSize()->getHeight();
            $height = $width / $ratio;
        }

        $image
            ->resize(new Box($width, $height))
            ->save($targetPath);

        return true;
    }
}