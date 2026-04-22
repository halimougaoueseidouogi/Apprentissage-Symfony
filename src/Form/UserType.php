<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            // ->add('password', PasswordType::class, [
            //     'label' => 'Votre mot de passe',
            //     'attr' => [
            //         'placeholder' => 'Saisissez votre mot de passe',
            //         'autocomplete' => 'new-password'],
            //     'required' => false,
            //     'mapped' => false,
            //     'constraints' => [
            //         // On n'ajoute les contraintes que si le champ n'est pas vide
            //         new PasswordStrength([
            //             'minScore' => PasswordStrength::STRENGTH_MEDIUM, // Définit le niveau requis
            //             'message' => 'Votre mot de passe est trop faible.'  
            //         ]),
            //         // Optionnel : si c'est la création, vous pouvez ajouter NotBlank ici
            //     ],
            // ])  
            ->add('nom')
            ->add('prenom')
            ->add('telephone')
            ->add('adresse')
            ->add('dateNaissance')
            // ->add('createdAt', null, [
            //     'widget' => 'single_text'
            // ])
            // ->add('updatedAt', NULL, [
            //     'widget' => 'single_text'
            // ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
