<?php

namespace App\Controller\Admin;

use App\Entity\VoteQuestion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VoteQuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VoteQuestion::class;
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
