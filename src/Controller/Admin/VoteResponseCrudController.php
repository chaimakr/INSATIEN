<?php

namespace App\Controller\Admin;

use App\Entity\VoteResponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VoteResponseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VoteResponse::class;
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
