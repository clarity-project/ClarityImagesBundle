<?php

namespace Clarity\ImagesBundle\Form\Strategy;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Clarity\CdnBundle\Filemanager\Filemanager;
use Clarity\CdnBundle\Filemanager\Common\ObjectInterface;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
abstract class AbstractCdnUploadStrategy implements UploadStrategyInterface
{
    /**
     * @var \Clarity\CdnBundle\Filemanager\Filemanager
     */
    protected $filemanager;

    /**
     * @param \Clarity\CdnBundle\Filemanager\Filemanager $filemanager
     */
    public function __construct(Filemanager $filemanager)
    {
        $this->filemanager = $filemanager;
    }

    /**
     * {@inheritDoc}
     */
    public function upload(UploadedFile $file)
    {
        $manipulator = $this->container->get('clarity_images.image.manipulator');
        
        $this->preUpload($file);

        $object = $this->filemanager->upload($file);
        if (!$object) {
            return null;
        }

        $this->postUpload($object);
        
        return $object->getUri();
    }

    /**
     * @param  \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return void
     */
    public function preUpload(UploadedFile $file)
    {
    }

    /**
     * @param  \Clarity\CdnBundle\Filemanager\Common\ObjectInterface $object
     * @return void
     */
    public function postUpload(ObjectInterface $object)
    {
    }

    /**
     * Returns storage name where image will be saved
     * 
     * @return string
     */
    abstract public function getStorage();
} 