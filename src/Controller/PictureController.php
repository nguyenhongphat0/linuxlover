<?php
/**
 * Created by PhpStorm.
 * User: nguyenhongphat0
 * Date: 1/3/18
 * Time: 11:41 PM
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PictureController extends Controller
{
    /**
     * @Route("/picture/upload", name="uploadpicture", methods="GET")
     */
    public function uploadPicture(SessionInterface $session) {
        if (!$session->get('user'))
            return $this->createNotFoundException();
        return $this->render('pages/upload-picture.html.twig');
    }

    /**
     * @Route("/picture/upload", name="savingpicture", methods="POST")
     */
    public function savingPicture(Request $request) {
        $picture = $request->files->get('picture');
        $name = $request->get('name');
        $picture->move("picture", $name);
        $this->addFlash('notice', 'Picture uploaded <a href="/picture/'.$name.'" target="_blank">here</a> successfuly');
        return $this->redirectToRoute('uploadpicture');
    }
}