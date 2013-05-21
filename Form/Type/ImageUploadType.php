<?php

namespace Clarity\ImagesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver;

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
     * @var string
     */
    protected $strategy;

    /**
     * @param \Symfony\Component\EventDispatcher\EventSubscriberInterface $subscriber
     */
    public function __construct(EventSubscriberInterface $subscriber)
    {
        $this->subscriber   = $subscriber;
        $this->strategy     = 'clarity_images.form.strategy.simple_upload';
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
            'data_class' => null,
            'upload_strategy' => $this->strategy,
            'save_property_path' => null,
            'error_bubbling' => true,
            'upload_route' => null,
            'use_ajax' => false,
            'upload_path' => null,
            'csrf_protection' => false,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['ajax'] = $options['use_ajax'];
        $view->vars['upload_route'] = $options['upload_route'];
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