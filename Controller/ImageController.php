<?php

namespace Clarity\ImagesBundle\Controller;

use Clarity\ImagesBundle\Form\Type\ImageUploadCropType;
use Clarity\ImagesBundle\Form\Type\ImageUploadType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @return Response
     */
    public function simpleAction(Request $request)
    {
        $form = $this->createForm(ImageUploadType::class);
        $id = $request->get('id');
        $uniqueKey = $request->get('unique_key');

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $options = $request->getSession()->get($uniqueKey);
                $image = $this->get($options['strategy'])->upload($form->getData());

                if (null !== $image) {
                    // @todo: it will be modified in new version
                    // if ($options['destroy']) {
                    //    $request->getSession()->remove($uniqueKey);
                    // }

                    return new Response(json_encode(array(
                        'uri' => $image->getSchemaPath(),
                        'url' => $image->getWebPath(),
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
     * @param Request $request
     * @return Response
     */
    public function cropAction(Request $request)
    {
        $id = $request->get('id');
        $uniqueKey = $request->get('unique_key');
        $crop = $request->get('crop', false);
        $options = $request->getSession()->get($uniqueKey);

        if (!$crop) {
            $form = $this->createForm(ImageUploadType::class);
        } else {
            $form = $this->createForm(ImageUploadCropType::class);
        }

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                if ($data instanceof UploadedFile) { // upload file and show crop tool
                    $image = $this->get($options['strategy'])->upload($form->getData());
                    $crop = true;
                    $form = $this->createForm(ImageUploadCropType::class, array('uri' => $image->getSchemaPath()));

                    return $this->render('ClarityImagesBundle:Image:crop_form.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'unique_key' => $uniqueKey,
                        'crop' => $crop
                    ));
                }

                // crop and send json response
                $image = $this->get($options['crop_strategy'])->handle($data, array(
                    'width' => $options['width'],
                    'height' => $options['height']
                ));

                return new Response(json_encode(array(
                    'uri' => $image->getSchemaPath(),
                    'url' => $image->getWebPath(),
                )));
            }
        }

        return $this->render('ClarityImagesBundle:Image:crop.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'unique_key' => $uniqueKey,
            'crop' => $crop,
            'width' => $options['width'],
            'height' => $options['height'],
        ));
    }
}
