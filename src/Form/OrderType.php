<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('addresses', EntityType::class, [
                'label' => false,
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'choice_label' => function(Address $address) {
                    return $address? '[b]'.$address->getName().'[b2][br]'.$address->getAddress().'[br]'.$address->getCity().' - '.$address->getCountry():'';
                }
            ])
            ->add('carriers', EntityType::class, [
                'label' => 'Choississez votre transporteur',
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'class' => Carrier::class,
                'choice_label' => function(Carrier $carrier) {
                    return $carrier? '[b]'.$carrier->getName().'[b2][br]'.$carrier->getDescription().'[br]'.
                     number_format($carrier->getPrice() / 100, 2, ',',' ')." €":'';
                }
            ])
            ->add("Submit", SubmitType::class, [
                'label' => 'Valider ma commande',
                'attr' => [
                    'class' => 'btn btn-success btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => null
        ]);
    }
}
