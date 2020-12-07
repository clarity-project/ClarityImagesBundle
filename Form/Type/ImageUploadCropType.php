<?php

namespace Clarity\ImagesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('uri', HiddenType::class, array('attr' => array('crop-input' => 'uri')))
            ->add('x', HiddenType::class, array('attr' => array('crop-input' => 'x')))
            ->add('y', HiddenType::class, array('attr' => array('crop-input' => 'y')))
            ->add('w', HiddenType::class, array('attr' => array('crop-input' => 'w')))
            ->add('h', HiddenType::class, array('attr' => array('crop-input' => 'h')))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
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
        return FormType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'clarity_image_crop';
    }
}
