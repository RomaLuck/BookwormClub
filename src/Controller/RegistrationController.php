<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'app_register')]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security                    $security,
        EntityManagerInterface      $entityManager,
        SerializerInterface         $serializer,
        ValidatorInterface          $validator
    ): Response
    {
        if ($request->isMethod('POST')) {
            try {
                $user = $serializer->deserialize($request->getContent(), User::class, 'json');

                $errors = $validator->validate($user);
                if (count($errors) > 0) {
                    return $this->json([
                        'message' => 'Validation failed', 'errors' => $errors
                    ], Response::HTTP_BAD_REQUEST);
                }

                $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
                if ($existingUser) {
                    return $this->json(['message' => 'User already exists'], Response::HTTP_CONFLICT);
                }

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $user->getPassword()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                $security->login($user, 'json_login', 'main');
                return $this->json([
                    'message' => 'User created!', 'user' => $user->toArray()
                ], Response::HTTP_CREATED);
            } catch (\Exception $e) {
                return $this->json([
                    'message' => 'An error occurred', 'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->json(['message' => 'Method is not correct'], Response::HTTP_BAD_REQUEST);
    }
}