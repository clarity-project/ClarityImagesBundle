<?php

namespace Clarity\ImagesBundle\Form\Strategy;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Clarity\ImagesBundle\Form\Exception;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class SimpleUploadStrategy implements UploadStrategyInterface
{
    /**
     * @var string
     */
    protected $kernelDir;

    /**
     * @var string
     */
    protected $uploadPath;

    /**
     * @param string $kernelDir
     */
    public function __construct($kernelDir)
    {
        $this->kernelDir = $kernelDir.'/web';
        $this->uploadPath = null;
    }

    /**
     * @param string $uploadPath
     * @return boolean
     * @throws \Clarity\ImagesBundle\Form\Exception\UploadStrategyException
     */
    public function setUploadPath($uploadPath)
    {
        $this->uploadPath = $uploadPath;

        if (!is_dir($this->kernelDir.$this->uploadPath)) {
            throw new Exception\UploadStrategyException(sprintf('Unable to find folder: "%s"', $this->kernelDir.$this->uploadPath));
        }

        if (!is_writable($this->kernelDir.$this->uploadPath)) {
            throw new Exception\UploadStrategyException(sprintf('Folder does not writable: "%s"', $this->kernelDir.$this->uploadPath));
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function upload(UploadedFile $file)
    {
        if (null === $this->uploadPath) {
            throw new Exception\UploadStrategyException('Upload path is required for SimpleUploadStrategy');
        }

        $filename = sha1(uniqid(mt_rand(), true));
        $name = $filename.'.'.$file->guessExtension();
        
        $file->move($this->kernelDir.$this->uploadPath, $name);

        return $this->uploadPath.'/'.$name;
    }
}