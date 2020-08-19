<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * Retrieves the collection of User resources.
     *
     * @Route("/", name="users", methods={"GET","HEAD"})
     */
    public function show(UserRepository $userRepository, SerializerInterface $serializer)
    {
        $users = $userRepository->findAll();

        $usersJson = $serializer->serialize($users, 'json');

        return JsonResponse::fromJsonString($usersJson, 200);
    }

    /**
     * Creates a User resource.
     *
     * @Route("/user/create", name="user_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $user = new User();

        $errors = [];
        if (!$this->validate($user, $request, $errors, $validator)) {
            return new JsonResponse($errors, 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return JsonResponse::fromJsonString('Saved new product with id '.$user->getId(), 200);
    }

    /**
     * Retrieves a User resource.
     *
     * @Route("/user/{id}", name="user_read", methods={"GET"})
     */
    public function read(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return JsonResponse::fromJsonString('No user found for id '.$id, 400);
        }

        $userJson = $serializer->serialize($user, 'json');

        return JsonResponse::fromJsonString($userJson, 200);
    }

    /**
     * Replaces the User resource.
     *
     * @Route("/user/update/{id}", name="user_update", methods={"POST"})
     */
    public function update(int $id, EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return JsonResponse::fromJsonString('No user found for id '.$id, 400);
        }

        $errors = [];
        if (!$this->validate($user, $request, $errors, $validator)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return JsonResponse::fromJsonString($errorsJson, 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return JsonResponse::fromJsonString('User '.$id.' updated', 200);
    }

    /**
     * Removes the User resource.
     *
     * @Route("/user/delete/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(int $id, EntityManagerInterface $entityManager)
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return JsonResponse::fromJsonString('No user found for id '.$id, 400);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return JsonResponse::fromJsonString('User '.$id.' deleted', 200);
    }

    /**
     * Validate data for the create and update events
     */
    private function validate(User $user, Request $request, array &$errors, ValidatorInterface $validator)
    {
        if (!empty($request->get('email')))
            $user->setEmail($request->get('email'));

        if (!empty($request->get('first_name')))
            $user->setFirstName($request->get('first_name'));

        if (!empty($request->get('last_name')))
            $user->setLastName($request->get('last_name'));

        $validationList = $validator->validate($user);
        $errors = [];
        foreach ($validationList as $validation) {
            $errors['errors'][] = $validation->getPropertyPath() . ': '.$validation->getMessage();
        }

        if (count($errors) > 0) {
            return false;
        }

        return true;
    }
}
