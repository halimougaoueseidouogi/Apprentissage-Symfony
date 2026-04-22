<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class UserController extends AbstractController
{
    public function __construct(
        private UserService $userService
        ){

    }
    
    #[Route('/', name: 'all_user')]
    public function index(UserRepository $UserRepository, EntityManagerInterface $em): Response
    {
        $users =  $UserRepository->findLastUsers(10);
        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
        //return $this->json(['users' => $users]);
    }

    //#[Route('/show/{id}-{name}', name: 'show_user', requirements: ['id' => '\d+', 'name' =>'[a-z0-9-]+'], defaults: ['id'=>1])]
    #[Route('/{id<\d+>?1}/show', name: 'show_user', requirements: ['id' => '\d+'])]
    public function show(int $id, UserRepository $userRepository): Response
    {
        $user =  $userRepository->find($id);
        // $comptes = 
         if(!$user){
            $this->addFlash('danger', 'Le client n\'existe pas');
            return $this->redirectToRoute('all_user');

        }
        return $this->render('user/show.html.twig', [
                'user' => $user
            ]);
    }
    #[Route('/create', name:'create_user')]
    public function create(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = New User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {            
            $hashedPassword = $passwordHasher->hashPassword($user, 'Azerty001@');
            $user->setPassword($hashedPassword);
            
            $user->setCreatedAtValue();
            $user->setUpdatedAtValue();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Utilisteur créer');
            return $this->redirectToRoute('all_user');

        }
        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'update_user')]
    public function edit( Request $request, User $user, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher):  Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $user->setUpdatedAtValue();
            $em->flush();
            $this->addFlash('success', 'Mise à jour effectuer');
            return $this->redirectToRoute('all_user');
        }
        // $user->setName();
        // $user->setEmail();
        // $user->setCreatedAt(new DateTimeImmutable());
        // $em->persist($user);
        // $em->flush();

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
        //return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //    'path' => 'src/Controller/UserController.php',
        //]);
    }

    #[Route('/{id<\d+>?1}/delete', name: 'delete_user', methods:['DELETE']) ]
    public function delete(int $id, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($id);
        if(!$user){
            $this->addFlash('danger', 'Le client n\'existe pas');
            return $this->redirectToRoute('all_user');

        }
        $em->remove($user);
        $em->flush();
        $this->addFlash('danger', 'Suppression effectuer');
        return $this->redirectToRoute('all_user');

        //return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //    'path' => 'src/Controller/UserController.php',
        //]);   
    }
}
