<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * Retrieves the collection of User resources.
     *
     * @Route("/", name="users", methods={"GET","HEAD"})
     */
    public function show(EntityManagerInterface $entityManager)
    {
        $query = $entityManager->createQuery('SELECT u FROM App:User u');

        $users = $query->getArrayResult();

        return new JsonResponse($users, 200);
    }

    /**
     * Creates a User resource.
     *
     * @Route("/user/create", name="user_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $user = new User();
        $user->setEmail($request->get('email'));
        $user->setFirstName($request->get('first_name'));
        $user->setLastName($request->get('last_name'));

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse('Saved new product with id '.$user->getId(), 200);
    }

    /**
     * Retrieves a User resource.
     *
     * @Route("/user/{id}", name="user_read", methods={"GET"})
     */
    public function read(int $id, EntityManagerInterface $entityManager)
    {
        $query = $entityManager->createQuery('SELECT u FROM App:User u WHERE u.id = '.$id);

        $user = $query->getArrayResult();

        if (!$user) {
            return new JsonResponse('No user found for id '.$id, 400);
        }

        return new JsonResponse($user, 200);
    }

    /**
     * Replaces the User resource.
     *
     * @Route("/user/update/{id}", name="user_update", methods={"POST"})
     */
    public function update(int $id, EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator)
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse('No user found for id '.$id, 400);
        }

        $user->setEmail($request->get('email'));
        $user->setFirstName($request->get('first_name'));
        $user->setLastName($request->get('last_name'));

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse('User '.$id.' updated', 200);
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
            return new JsonResponse('No user found for id '.$id, 400);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse('User '.$id.' deleted', 200);
    }
}
