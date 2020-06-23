<?php

namespace App\Controller\Admin;

use App\Entity\ContactMail;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ContactMailCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContactMail::class;
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
