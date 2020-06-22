<?php


namespace App\Security;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $request->getSession()->get(Security::LAST_USERNAME)]);
//        dd($user->getRoles());
        if($user->getRoles()[0]=="ROLE_STUDENT"){
            return new RedirectResponse('/student');
        }
        elseif($user->getRoles()[0]=="ROLE_TEACHER"){
            return new RedirectResponse('/teacher');
        }
        else
            return new RedirectResponse('/');

    }
}