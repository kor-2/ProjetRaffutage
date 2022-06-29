<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FactureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Facture::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnform(),
            TextField::new('numero'),
            TextField::new('client'),
            BooleanField::new('paye'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->disable(Action::NEW)
        ->disable(Action::DELETE);
    }
    

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
