<?php

namespace Clarity\ImagesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class ImageUploadCropType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uri', 'hidden', array('attr' => array('crop-input' => 'uri')))
            ->add('x', 'hidden', array('attr' => array('crop-input' => 'x')))
            ->add('y', 'hidden', array('attr' => array('crop-input' => 'y')))
            ->add('w', 'hidden', array('attr' => array('crop-input' => 'w')))
            ->add('h', 'hidden', array('attr' => array('crop-input' => 'h')))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'form';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'clarity_image_crop';
    }
}