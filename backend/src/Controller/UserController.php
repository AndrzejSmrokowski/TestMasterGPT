<?php
declare(strict_types=1);

namespace App\Controller;

use App\Exception\UserAlreadyExistsException;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/user', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
            $user = $this->userService->createUser($data);
        } catch (UserAlreadyExistsException $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }

        return $this->json(['success' => true, 'message' => 'User created!', 'username' => $user->getUsername()], 201);
    }

}