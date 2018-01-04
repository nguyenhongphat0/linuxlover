<?php
/**
 * Created by PhpStorm.
 * User: nguyenhongphat0
 * Date: 1/2/18
 * Time: 10:15 PM
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(SessionInterface $session) {
        $user = $session->get('user');
        return $this->render('pages/index.html.twig', ['user' => $user]);
    }
}