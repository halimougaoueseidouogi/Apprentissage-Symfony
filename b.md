use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// ...

#[Route('/{id<\d+>}/edit', name: 'update_user')]
public function edit(
    Request $request, 
    User $user, // Symfony trouve l'utilisateur automatiquement grâce à l'ID
    EntityManagerInterface $em, 
    UserPasswordHasherInterface $passwordHasher
): Response {
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // 1. Récupérer le mot de passe saisi dans le formulaire
        $plainPassword = $form->get('password')->getData();

        // 2. Si un nouveau mot de passe a été saisi, on le hache
        if ($plainPassword) {
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }

        $em->flush();
        
        $this->addFlash('success', 'Mise à jour effectuée');
        return $this->redirectToRoute('all_user');
    }

    return $this->render('user/edit.html.twig', [
        'form' => $form->createView(),
        'user' => $user
    ]);
}



#[Route('/user/create', name: 'create_user')]
public function create(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
{
    $user = new User(); // On crée l'instance ici
    
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Hachage du mot de passe
        $plainPassword = $form->get('password')->getData();
        if ($plainPassword) {
            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
        }

        $em->persist($user); // Important pour la création !
        $em->flush();

        $this->addFlash('success', 'Utilisateur créé !');
        return $this->redirectToRoute('all_user');
    }

    return $this->render('user/create.html.twig', [
        'form' => $form->createView(),
    ]);
}
