<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
                ->add("index", "detail");
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(["createdAt" => "DESC"]);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            DateTimeField::new('createdAt', 'Passé le'),
            TextField::new('user.fullname', 'Utilisateur'),
            MoneyField::new('total', 'Total')->setCurrency("EUR"),
            TextField::new('carrierName', 'Transporteur')->setTextAlign("center"),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency("EUR"),
            BooleanField::new('isPaid', 'Payée'),
            ArrayField::new('orderDetails', "produits achétés")->hideOnIndex() ,
            TextEditorField::new('delivery', 'Adresse de livraison')->hideOnIndex()         
        ];
    }
    
}
