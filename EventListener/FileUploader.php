<?php

namespace Clarity\ImagesBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class FileUploader
{
    /**
     * 
     * @var \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    protected $formSubscriber;

    /**
     * @param \Symfony\Component\EventDispatcher\EventSubscriberInterface $formSubscriber
     */
    public function __construct(EventSubscriberInterface $formSubscriber)
    {
        $this->formSubscriber = $formSubscriber;
    }

    /**
     * @param  \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (null !== $this->formSubscriber->getRootDataClass()) {
            $options = $this->formSubscriber->getOptions();
            $className = $options['uploadable_class'];
            if ($entity instanceof $className) {
                if (null !== $strategy = $this->formSubscriber->getStrategy()) {
                    $uploadedFile = $strategy->upload($this->formSubscriber->getFile());
                    if (null !== $uploadedFile) {
                        $propertyMethod = $this->formSubscriber->getPropertyMethod();
                        if (!method_exists($entity, $propertyMethod)) {
                            throw new \InvalidArgumentException(sprintf('Method "%s" in class "%s" does not exists. Please verify "%s" type options', $propertyMethod, $className, 'image_upload.'));
                        }

                        $entity->{$propertyMethod}($uploadedFile);
                    }
                }
            }
        }
    }
}