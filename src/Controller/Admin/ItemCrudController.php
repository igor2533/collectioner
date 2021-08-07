<?php

namespace App\Controller\Admin;
use App\Entity\Item;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
class ItemCrudController extends AbstractCrudController
{



    public static function getEntityFqcn(): string
    {
        return Item::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('title'),
            TextEditorField::new('description'),
             AssociationField::new('category'),
            TextField::new('status'),
            TextField::new('likes'),
            TextField::new('date_created'),
            TextField::new('date_modife'),
            TextField::new('author'),
            TextField::new('year'),
            TextField::new('slug'),

        ];
    }

}
