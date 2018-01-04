<?php
/**
 * Created by PhpStorm.
 * User: nguyenhongphat0
 * Date: 1/2/18
 * Time: 9:35 PM
 */

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils) {
        if ($this->getUser())
            throw new AccessDeniedException();
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('pages/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {}

    /**
     * @Route("/signin", name="signin", methods="GET")
     */
    public function signin() {
        if ($this->getUser())
            throw new AccessDeniedException();
        return $this->render("pages/signin.html.twig", ['message' => '']);
    }

    /**
     * @Route("/signin", name="register", methods="POST")
     */
    public function register(Request $request) {
        if ($this->getUser())
            throw new AccessDeniedException();
        $username = $request->get('username');
        $password = $request->get('password');
        $repass = $request->get('re_password');
        $repo = $this->getDoctrine()->getRepository(User::class);
        if ($repo->findByUsername($username))
            return $this->render("pages/signin.html.twig", ['message' => 'Username existed, please try another']);
        if ($password != $repass)
            return $this->render("pages/signin.html.twig", ['message' => 'Password not match']);
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRoles('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->addFlash('notice', 'Your account was created successfully! Now you can login with your account');
        return $this->redirectToRoute('login');
    }
}