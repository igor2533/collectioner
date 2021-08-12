<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Item;
use App\Entity\Tag;
use App\Repository\ItemRepository;
use App\Repository\CategoryRepository;
use App\Controller\SecurityController;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class IndexController extends AbstractController
{

    public function index(CategoryRepository $categoryRepository,ItemRepository $itemRepository):Response {
        $items = $itemRepository->findAll();
        $categories = $categoryRepository->findAll();
        return $this->render('home.html.twig',
        ['items' => $items,
            'categories' => $categories
            ]);

    }
    public function category(ItemRepository $itemRepository,CategoryRepository $categoryRepository,string $slug, Request $request):Response {

        $category = $categoryRepository->findOneBy(array(

            'slug' => $request->get('slug'),
        ));
         //die($category->getId());
        $items = $itemRepository->findBy(array(
            'category' => $category->getId()
        ));


        return $this->render('category-page.html.twig',
            ['category' => $category,
                'items' => $items
                ]);

    }


    public function show(ItemRepository $itemRepository,string $slug, Request $request):Response {

        $item = $itemRepository->findOneBy(array(

            'slug' => $request->get('slug'),
        ));

        return $this->render('view.html.twig',
            ['item' => $item,
               ]);

    }





}