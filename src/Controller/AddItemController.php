<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\AddItemFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AddItemController extends AbstractController
{
    #[Route('/add_item', name: 'app_add_item')]
    public function add_item(Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(AddItemFormType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
           // $item->setRoles(array('ROLE_USER'));

              $item->setSlug($item->getTitle()) ;

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('index');
        }

        return $this->render('item/add.html.twig', [
            'addItemForm' => $form->createView(),
        ]);
    }
}
