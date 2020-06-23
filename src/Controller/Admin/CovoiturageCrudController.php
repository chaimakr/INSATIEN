<?php

namespace App\Controller\Admin;

use App\Entity\Covoiturage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CovoiturageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Covoiturage::class;
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
