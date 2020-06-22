<?php

namespace App\Controller\Admin;

use App\Entity\RequestFromTeacher;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RequestFromTeacherCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RequestFromTeacher::class;
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
