<?php

namespace App\Controller\Admin;

use App\Entity\TypeCouteau;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TypeCouteauCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeCouteau::class;
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
