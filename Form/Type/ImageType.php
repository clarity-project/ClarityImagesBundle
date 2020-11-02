<?php

namespace Clarity\ImagesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class ImageType extends AbstractType
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'strategy',
        ));

        $resolver->setDefaults(array(
            'in_collection' => false,
            'crop' => array(
                'enabled' => false,
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $uniqueKey = md5(microtime().rand());
        $sessionData = array();

        if (null !== $options['strategy']) {
            $sessionData['strategy'] = $options['strategy'];
        }

        if (isset($options['crop']['width'])) {
            $sessionData['width'] = (int) $options['crop']['width'];
        }

        if (isset($options['crop']['height'])) {
            $sessionData['height'] = (int) $options['crop']['height'];
        }

        if (isset($options['crop']['strategy'])) {
            $sessionData['crop_strategy'] = $options['crop']['strategy'];
        }

        if (isset($options['in_collection'])) {
            $sessionData['destroy'] = !$options['in_collection'];
        }

        $this->session->set($uniqueKey, $sessionData);
        $view->vars['unique_key'] = $uniqueKey;
        $view->vars['crop'] = $options['crop']['enabled'];
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return HiddenType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'clarity_image';
    }
}
