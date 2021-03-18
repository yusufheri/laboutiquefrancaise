<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('firstname', TextType::class, [
                'label' => 'Mon prénom',
                'disabled' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Mon nom',
                'disabled' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Mon email',
                'disabled' => true
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Mon mot de passe actuel',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Mon actuel mot de passe'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => new Length(6, 6, 25, null, null,null,"Cette chaîne est trop courte. Elle doit avoir au minimum 6 caractères.","Cette chaîne est trop longue. Elle doit avoir au maximum 25 caractères."),
                'invalid_message' => 'Les deux mots de passe et la confirmation doivent être identiques.',
                'required' => true,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Votre nouveau mot de passe', 
                    'attr' => ['placeholder' => 'Merci de saisir un mot de passe']
                ],
                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe', 
                    'attr' => ['placeholder' => 'Merci de confirmer votre nouveau mot de passe']
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
