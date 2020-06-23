<?php

namespace App\Controller\Admin;

use App\Entity\RequestFromStudent;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RequestFromStudentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RequestFromStudent::class;
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
