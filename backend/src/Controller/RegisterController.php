<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: 'POST')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $data = json_decode($request->getContent(), true);
        $formData = $data['registration_form'] ?? null;

        if (null === $formData) {
            return $this->json(['success' => false, 'message' => 'Missing required parameters'], 400);
        }

        $user = UserFactory::createUser($formData['username'], $formData['email']);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->submit($formData);

        if (!$form->isValid()) {
            $errors = (string) $form->getErrors(true, false);
            return $this->json(['success' => false, 'message' => 'Invalid data', 'errors' => $errors], 400);
        }

        $plainPassword = $formData['plainPassword']['first'];
        $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));

        try {
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => 'An error occurred while registering. Please try again later.', 'error' => $e->getMessage()], 500);
        }

        return $this->json(['success' => true, 'message' => 'Registration successful!'], 201);
    }
}
