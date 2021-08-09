<?php

namespace App\Controller\Admin;
use App\Entity\Item;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField as ImageFields;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Ausi\SlugGenerator\SlugGenerator;

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
            ChoiceField::new('status')
                ->setChoices([  'Опубликовано' => '1',
                        'Не опубликовано' => '0',
                       ]
                ),



            AssociationField::new('author'),
            TextField::new('year'),

            TextField::new('slug'),
            ImageFields::new('image')
                ->setBasePath(' uploads/')
                ->setUploadDir('public/uploads')->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),

        ];
    }

}
