<?php

namespace App\Controller\Admin;

use App\Entity\QuizAnswer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class QuizAnswerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return QuizAnswer::class;
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
