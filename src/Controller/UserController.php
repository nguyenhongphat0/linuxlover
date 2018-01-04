<?php
/**
 * Created by PhpStorm.
 * User: nguyenhongphat0
 * Date: 1/2/18
 * Time: 9:35 PM
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/login", name="login", methods="GET")
     */
    public function login(SessionInterface $session) {
        if ($session->has('user'))
            return $this->redirectToRoute('home');
        return $this->render('pages/login.html.twig');
    }

    /**
     * @Route("/login", name="checkLogin", methods="POST")
     */
    public function checkLogin(Request $request, SessionInterface $session) {
        $username = $request->get('username');
        $password = $request->get('password');
        if ($username == 'nguyenhongphat0' && $password == 'hongphat') {
            $session->set('user', 'nguyenhongphat0');
            return $this->redirectToRoute('home');
        }
        else {
            $this->addFlash('error', 'Incorrect username or password!');
            return $this->redirectToRoute('login');
        }
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logOut(SessionInterface $session) {
        $session->remove('user');
        return $this->redirectToRoute('login');
    }
}