<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    private $serializer;
    private $userRepository;

    /**
     * AdvertController constructor.
     * @param SerializerInterface $serializer
     * @param UserRepository $userRepository
     */
    public function __construct(SerializerInterface $serializer, UserRepository $userRepository)
    {
        $this->serializer=$serializer;
        $this->userRepository=$userRepository;
    }

    /**
     * @Route("/register",name="register", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return JsonResponse
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

        $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
        $user->setRoles(["ROLE_USER"]);

        $this->userRepository->add($user);

        return new JsonResponse('created!', Response::HTTP_CREATED, [], true);
    }
}
