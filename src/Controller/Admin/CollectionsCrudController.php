<?php

namespace App\Controller\Admin;

use App\Entity\Collection;
use App\Entity\Collections;
use App\Entity\Items;
use App\Entity\Item;
use App\Form\AddItemFormType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Speicher210\CloudinaryBundle\Cloudinary\Uploader;

class CollectionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Collections::class;
    }

//    public function createEntity(string $entityFqcn)
//    {
//
//        $collection = new Collections::class;
//
//
//
//
//        return $collection;
//    }

    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('title'),

            AssociationField::new('author'),
            AssociationField::new('collection'),

        ];
    }

}
