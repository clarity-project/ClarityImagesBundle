<?php

namespace Clarity\ImagesBundle\Form\Strategy;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Clarity\CdnBundle\Filemanager\Filemanager;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
abstract class AbstractCdnStrategy implements StrategyInterface
{
    /**
     * @var \Clarity\CdnBundle\Filemanager\Filemanager
     */
    protected $filemanager;

    /**
     * @param \Clarity\CdnBundle\Filemanager\Filemanager $filemanager
     */
    public function __construct($filemanager)
    {
        $this->filemanager = $filemanager;
    }

    /**
     * @return string
     */
    abstract public function getStorageName();

    /**
     * @return string
     */
    abstract public function getContainerName();

    /**
     * {@inheritDoc}
     */
    public function upload(UploadedFile $file)
    {
        return $this->filemanager->upload($file, $this->getContainerName(), $this->getStorageName());
    }
}