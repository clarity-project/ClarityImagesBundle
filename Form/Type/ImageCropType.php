<?php

namespace Clarity\ImagesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class ImageCropType extends AbstractType
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
    protected $image;

    /**
     * @param \Symfony\Component\EventDispatcher\EventSubscriberInterface $subscriber
     */
    public function __construct(EventSubscriberInterface $subscriber)
    {
        $this->subscriber = $subscriber;
        $this->image = null;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->subscriber->setFormType($this);
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
            'crop_strategy' => 'clarity_images.form.strategy.simple_crop',
            'height' => 0,
            'width' => 0,
            'compound' => true,
            'error_bubbling' => false,
            'crop_sizes' => array(),
            'use_ajax' => true,
            'upload_route' => null,
            'crop_route' => null,
        ));
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['crop'] = false;
        $view->vars['ajax'] = $options['use_ajax'];
        $view->vars['upload_route'] = $options['upload_route'];
        $view->vars['crop_route'] = $options['crop_route'];

        if (null !== $this->image) {
            $view->vars['image'] = $this->image;
            $view->vars['crop'] = true;
        }
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
        return 'image_crop';
    }
}