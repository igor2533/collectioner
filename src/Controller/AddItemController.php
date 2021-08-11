<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\AddItemFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;
use Symfony\Component\String\Slugger\SluggerInterface;
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
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $slugify = new Slugify();
                $safeFilename = $slugify->slugify($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $item->setImage($newFilename);
            }



            // encode the plain password
           // $item->setRoles(array('ROLE_USER'));
            $slugify = new Slugify();

            //$slugify = $slugify."_".strval($item->getId());
              $item->setSlug($slugify->slugify($item->getTitle()."_".$item->getId()));
                $item->setDateCreated(date("m.d.y"));
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
