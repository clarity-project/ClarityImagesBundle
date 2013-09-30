<?php

namespace Clarity\ImagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class ImageController extends Controller
{
    /**
     * This action is actually for type without crop.
     * Calls ImageUploadType
     * 
     * It handles both GET and POST requests
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function simpleAction()
    {
        $form = $this->createForm('clarity_image_upload');
        $id = $this->getRequest()->get('id');
        $uniqueKey = $this->getRequest()->get('unique_key');

        if ($this->getRequest()->isMethod('post')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $options = $this->getRequest()->getSession()->get($uniqueKey);
                $image = $this->get($options['strategy'])->upload($form->getData());
                if (null !== $image) {
                    $this->getRequest()->getSession()->remove($uniqueKey);
                    return new Response(json_encode(array(
                        'uri' => $image->getUri(),
                        'url' => $image->getHttpUri(),
                    )));
                }
            }
        }

        return $this->render('ClarityImagesBundle:Image:simple.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'unique_key' => $uniqueKey,
        ));
    }

    /**
     * This action is actually for type with crop.
     * Calls ImageUploadCropType
     * 
     * It handles both GET and POST requests
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cropAction()
    {
        $crop = $this->getRequest()->get('crop', false);
        if (!$crop) {
            $form = $this->createForm('clarity_image_upload');
        } else {
            $form = $this->createForm('clarity_image_crop');
        }
        $id = $this->getRequest()->get('id');
        $uniqueKey = $this->getRequest()->get('unique_key');

        if ($this->getRequest()->isMethod('post')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $data = $form->getData();
                if ($data instanceof UploadedFile) {
                    $options = $this->getRequest()->getSession()->get($uniqueKey);
                    $image = $this->get($options['strategy'])->upload($form->getData());
                    $crop = true;
                    $form = $this->createForm('clarity_image_crop', array('uri' => $image->getUri()));
                    
                    return $this->render('ClarityImagesBundle:Image:crop_form.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'unique_key' => $uniqueKey,
                        'crop' => $crop
                    ));
                }
            }
        }
        
        
        return $this->render('ClarityImagesBundle:Image:crop.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'unique_key' => $uniqueKey,
            'crop' => $crop
        ));
    }
}