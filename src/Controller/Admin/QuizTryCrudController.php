<?php

namespace App\Controller\Admin;

use App\Entity\QuizTry;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class QuizTryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return QuizTry::class;
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
