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
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        $options = $form->getConfig()->getType()->getOptionsResolver()->resolve();

        if ($event->getData() instanceof UploadedFile) {
            $uploadStrategy = $options['upload_strategy'];
            try {
                $strategy = $this->container->get($uploadStrategy);
            } catch (ServiceNotFoundException $e) {
                $strategy = new $uploadStrategy();
            }

            if (!$strategy instanceof UploadStrategyInterface) {
                throw new Exception\UploadStrategyException(sprintf('Class "%s" must implement "%s"', $uploadStrategy, 'Clarity\ImagesBundle\Form\Strategy\UploadStrategyInterface'));
            }

            // handle situation if we use our default upload strategy: SimpleUploadStrategy
            if ($strategy instanceof SimpleUploadStrategy) {
                if (!isset($options['upload_path'])) {
                    throw new Exception\UploadStrategyException(sprintf('You are using "%s". This upload strategy requires "%s" option. For Example "%s"', 'Clarity\ImagesBundle\Form\Strategy\SimpleUploadStrategy', 'upload_path', 'web/uploads/images'));
                }
                $strategy->setUploadPath($options['upload_path']);
            }

            $uploadedFile = $strategy->upload($event->getData());
            $event->setData($uploadedFile);

            $factory = $this->container->get('form.factory');
            // adding crop fields
            $form
                ->add($factory->createNamed('image', 'hidden', array('data' => $uploadedFile)))
                ->add($factory->createNamed('x', 'hidden'))
                ->add($factory->createNamed('y', 'hidden'))
                ->add($factory->createNamed('w', 'hidden'))
                ->add($factory->createNamed('h', 'hidden'))
            ;
        }

        return $event;
    }
}