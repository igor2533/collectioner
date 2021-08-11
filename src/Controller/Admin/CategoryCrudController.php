<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField as ImageFields;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Cocur\Slugify\Slugify;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }


    public function configureFields(string $pageName): iterable
    {

        return [

            TextField::new('title'),
            ImageFields::new('image')
                ->setBasePath(' uploads/images/')
                ->setUploadDir('public/uploads/images')->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('slug'),
        ];
    }


    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            // adds the CSS and JS assets associated to the given Webpack Encore entry
            // it's equivalent to adding these inside the <head> element:
            // {{ encore_entry_link_tags('...') }} and {{ encore_entry_script_tags('...') }}
            //->addWebpackEncoreEntry('admin-app')

            // it's equivalent to adding this inside the <head> element:
            // <link rel="stylesheet" href="{{ asset('...') }}">
//            ->addCssFile('build/admin.css')
//            ->addCssFile('https://example.org/css/admin2.css')

            // it's equivalent to adding this inside the <head> element:
            // <script src="{{ asset('...'') }}"></script>
            //->addJsFile('build/admin.js')
            //->addJsFile('https://example.org/js/admin2.js')

            // use these generic methods to add any code before </head> or </body>
            // the contents are included "as is" in the rendered page (without escaping them)
            //->addHtmlContentToHead('<link rel="dns-prefetch" href="https://assets.example.com">')
            ->addHtmlContentToBody("<script src='https://code.jquery.com/jquery-1.8.3.js'></script>")
            ->addHtmlContentToBody("<script src='/assets/jquery.stringtoslug.min.js'></script>")
            ->addHtmlContentToBody("<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/speakingurl/6.0.0/speakingurl.min.js'></script>")
            ->addHtmlContentToBody("<script> $(document).ready( function() { $('input#Category_title').stringToSlug();$('input#Category_title').stringToSlug({setEvents: 'blur',getPut: 'input#Category_slug'});});</script>")
            //->addHtmlContentToBody('<!-- generated at '.time().' -->')
            ;
    }



    public function createEntity(string $entityFqcn)
    {


    }


}
