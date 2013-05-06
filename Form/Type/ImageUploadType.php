<?php

namespace Clarity\ImagesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class ImageUploadType extends AbstractType
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    protected $subscriber;

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolverInterface
     */
    protected $resolver;

    /**
     * @param \Symfony\Component\EventDispatcher\EventSubscriberInterface $subscriber
     */
    public function __construct(EventSubscriberInterface $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber($this->subscriber);

        $this->resolver->setDefaults($this->resolver->resolve($options));
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->resolver = $resolver;

        $resolver->setDefaults(array(
            'data_class' => 'Symfony\Component\HttpFoundation\File\UploadedFile',
            'upload_strategy' => 'clarity_images.form.strategy.simple_upload',
            'upload_path' => null,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'image_upload';
    }
}