<?php

namespace App\Form;

use App\Data\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder
                ->add('search', TextType::class, [
                    'label' => 'Recherche',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Faites votre recherche',
                        'class' => 'form-control-sm'
                    ]
                ])
                ->add('categories', EntityType::class, [
                    'label' => false,
                    'class' => Category::class,
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'Filtrer',
                    'attr' => [
                        'class' => 'btn btn-block btn-primary'
                    ]
                ])
                ;
                

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'crsf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}