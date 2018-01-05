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
     * @Route("/signup", name="signup", methods="GET")
     */
    public function signup() {
        if ($this->getUser())
            throw new AccessDeniedException();
        return $this->render("pages/signup.html.twig", ['message' => '']);
    }

    /**
     * @Route("/signup", name="register", methods="POST")
     */
    public function register(Request $request) {
        if ($this->getUser())
            throw new AccessDeniedException();
        $username = $request->get('username');
        $password = $request->get('password');
        $repass = $request->get('re_password');
        $repo = $this->getDoctrine()->getRepository(User::class);
        if ($repo->findByUsername($username))
            return $this->render("pages/signup.html.twig", ['message' => 'Xin lỗi, tên tài khoản của bạn đã bị trùng, vui lòng chọn tên tài khoản khác bạn nhé']);
        if ($password != $repass)
            return $this->render("pages/signup.html.twig", ['message' => 'Mật khẩu không khớp']);
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRoles('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->addFlash('notice', 'Tài khoản của bạn đã được tạo thành công. Giờ bạn đã có thể đăng nhập bằng tài khoản vừa tạo');
        return $this->redirectToRoute('login');
    }
}