<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    public function index(ItemRepository $itemRepository):Response {
        $items = $itemRepository->findAll();
        return $this->render('home.html.twig',
        ['items' => $items ]);

    }



}