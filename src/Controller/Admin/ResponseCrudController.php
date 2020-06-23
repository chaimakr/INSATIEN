<?php

namespace App\Controller\Admin;

use App\Entity\Response;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ResponseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Response::class;
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
