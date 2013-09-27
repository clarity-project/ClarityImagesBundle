<?php

namespace Clarity\ImagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

        return $this->render('ClarityImagesBundle:Image:simple.html.twig', array(
            'form' => $form->createView(),
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
        $form = $this->createForm('clarity_image_upload_crop');

        return $this->render();
    }
}