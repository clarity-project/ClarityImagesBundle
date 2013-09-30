<?php

namespace Clarity\ImagesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class ImageType extends AbstractType
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'strategy'
        ));

        $resolver->setDefaults(array(
            'crop' => array(
                'enabled' => false,
            )
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

        $this->session->set($uniqueKey, $sessionData);
        $view->vars['unique_key'] = $uniqueKey;
        $view->vars['crop'] = $options['crop']['enabled'];
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'clarity_image';
    }
}