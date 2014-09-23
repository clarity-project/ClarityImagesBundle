<?php

namespace Clarity\ImagesBundle\Form\Strategy;

use Clarity\ImagesBundle\Image\Manipulator;
use Clarity\CdnBundle\Filemanager\Filemanager;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
abstract class AbstractCdnCropStrategy implements CropStrategyInterface
{
    /**
     * @var \Clarity\ImagesBundle\Image\Manipulator
     */
    protected $manipulator;

    /**
     * @var \Clarity\CdnBundle\Filemanager\Filemanager
     */
    protected $filemanager;

    /**
     * @param \Clarity\CdnBundle\Filemanager\Filemanager $filemanager
     * @param \Clarity\ImagesBundle\Image\Manipulator $manipulator
     */
    public function __construct(Filemanager $filemanager, Manipulator $manipulator)
    {
        $this->filemanager = $filemanager;
        $this->manipulator = $manipulator;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(array $data, array $resize)
    {
        $object = $this->filemanager->get($data['uri']);
        $image = $this->manipulator->getImage($object->getFullPath());
        
        // get coord data
        $coords = array(
            'w' => $data['w'],
            'h' => $data['h'],
            'x' => $data['x'],
            'y' => $data['y'],
        );

        $fileUri = $this->filemanager->addDimensionToName($object->getSchemaPath(), $resize['width'].'x'.$resize['height']);
        $filepath = $this->filemanager->addDimensionToName($object->getFullPath(), $resize['width'].'x'.$resize['height']);
        
        // make crop
        if (!$this->manipulator->crop($image, $coords, $filepath)) {
            return false;
        }

        // resizing
        if (!$this->manipulator->resize($filepath, $resize['width'], $resize['height'], $filepath)) {
            return false;
        }

        return $this->filemanager->get($fileUri);
    }
}
