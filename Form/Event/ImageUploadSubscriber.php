<?php

namespace Clarity\ImagesBundle\Form\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Clarity\ImagesBundle\Form\Exception;
use Clarity\ImagesBundle\Form\Strategy\UploadStrategyInterface;
use Clarity\ImagesBundle\Form\Strategy\SimpleUploadStrategy;
use Symfony\Component\Form\FormInterface;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class ImageUploadSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $rootDataClass;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected $file;

    /**
     * @var \Clarity\ImagesBundle\Form\Strategy\UploadStrategyInterface
     */
    protected $strategy;

    /**
     * @var string
     */
    protected $property;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container        = $container;
        $this->rootDataClass    = null;
        $this->options          = array();
        $this->file             = null;
        $this->strategy         = null;
        $this->property         = null;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_BIND => 'onPreBind',
        );
    }

    /**
     * @param  DataEvent $event
     * @return DataEvent
     */
    public function onPreBind(DataEvent $event)
    {
        $form = $event->getForm();

        // setting root data class
        $this->setRootDataClass($event->getForm()->getRoot());

        $this->options = $form->getConfig()->getType()->getOptionsResolver()->resolve();

        if ($event->getData() instanceof UploadedFile) {
            $this->file = $event->getData();

            $this->setStrategy($this->options['upload_strategy']);
            $this->setPropertyPath($form, $this->options);
        }

        return $event;
    }

    /**
     * @param \Clarity\ImagesBundle\Form\Strategy\UploadStrategyInterface|string $strategy
     * @return \Clarity\ImagesBundle\Form\Event\ImageUploadSubscriber
     */
    public function setStrategy($strategy) 
    {
        if ($strategy instanceof UploadStrategyInterface) {
            $this->strategy = $strategy;

            return $this;
        }

        $strategyName = $strategy;

        try {
            $strategy = $this->container->get($strategyName);
        } catch (ServiceNotFoundException $e) {
            try {
                $strategy = new $strategyName();
            } catch (\Exception $e) {
                throw new Exception\UploadStrategyException(sprintf('Class or service "%s" not found.', $strategyName));
            }
        }

        if (!$strategy instanceof UploadStrategyInterface) {
            throw new Exception\UploadStrategyException(sprintf('Class "%s" must implement "%s"', $uploadStrategy, 'Clarity\ImagesBundle\Form\Strategy\UploadStrategyInterface'));
        }

        // handle situation if we use our default upload strategy: SimpleUploadStrategy
        if ($strategy instanceof SimpleUploadStrategy) {
            if (!isset($this->options['upload_path'])) {
                throw new Exception\UploadStrategyException(sprintf('You are using "%s". This upload strategy requires "%s" option. For Example "%s"', 'Clarity\ImagesBundle\Form\Strategy\SimpleUploadStrategy', 'upload_path', 'web/uploads/images'));
            }
            $strategy->setUploadPath($this->options['upload_path']);
        }

        return $this->setStrategy($strategy);
    }

    /**
     * @return \Clarity\ImagesBundle\Form\Strategy\UploadStrategyInterface
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @return \Clarity\ImagesBundle\Form\Event\ImageUploadSubscriber 
     */
    public function setRootDataClass(FormInterface $form)
    {
        if (is_object($form->getData())) {
            $this->rootDataClass = get_class($form->getData());    
        }
        
        return $this;
    }

    /**
     * @return string
     */
    public function getRootDataClass()
    {
        return $this->rootDataClass;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param FormInterface $form
     * @param array         $options
     * @return ImageUploadSubscriber
     */
    public function setPropertyPath(FormInterface $form, array $options)
    {
        if (null === $options['save_property_path']) {
            if ($form->getPropertyPath()->getLength() < 1) {
                throw new Exception\UploadStrategyException(sprintf('You must specify "%s" or "%s" option for file saving. It is must be mapped property.', 'property_path', 'save_property_path'));
            }
            
            $this->property = (string) $form->getPropertyPath();
        }
        
        $this->property = $options['save_property_path'];

        return $this;
    }

    /**
     * @return string
     */
    public function getPropertyMethod()
    {
        return (string) 'set' . ucfirst($this->property);
    }
}