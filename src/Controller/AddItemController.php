<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Tag;
use App\Form\AddItemFormType;
use App\Form\TagType;
use Cloudinary\Cloudinary;
use ContainerBIxhQvB\getSpeicher210Cloudinary_ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Speicher210\CloudinaryBundle\Speicher210CloudinaryBundle;
use Symfony\Config\Speicher210Cloudinary;
use Speicher210\CloudinaryBundle\DependencyInjection\Configuration;
use Speicher210\CloudinaryBundle\Cloudinary\Uploader;
class AddItemController extends AbstractController
{







    #[Route('/add_item', name: 'app_add_item')]
    public function add_item(Request $request,UserInterface $user): Response
    {

       //$cloudinary = new Cloudinary('cloudinary://345414795685931:aUt9VgLGEcxorWN6AvhILPBQc5Y@karasika');

        //$cloudinary->uploadApi->upload('https://evropochta.by/UserFiles/950200%20%D0%BD%D0%B0%20%D1%81%D0%B0%D0%B9%D1%82.png');
     $item = new Item();
       $tag = new Tag();


        $form = $this->createForm(AddItemFormType::class, $item);
        $form_tag = $this->createForm(TagType::class,$tag);


        $form->handleRequest($request);
        $form_tag->handleRequest($request);

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
                $file_ss = 'https://localhost:8000/uploads/images/'.$newFilename;
                Uploader::upload($file_ss);



            }



            // encode the plain password
           // $item->setRoles(array('ROLE_USER'));
            $slugify = new Slugify();

            //$slugify = $slugify."_".strval($item->getId());
              $item->setSlug($slugify->slugify($item->getTitle()."_".$item->getId()));
             $item->setDateCreated(date("m.d.y"));
          $item->setAuthor($this->getUser());
            $item->setStatus(1);
            $item->setLikes(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('index');
        }









        if ($form_tag->isSubmitted() && $form_tag->isValid()) {




           $slugify = new Slugify();

           //$slugify = $slugify."_".strval($item->getId());
           $tag->setSlug($slugify->slugify($tag->getTitle()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('create_item');
        }



        return $this->render('item/add.html.twig', [
            'addItemForm' => $form->createView(),
            'addTagForm' => $form_tag->createView()
        ]);
    }

    public function upload($file, $options = []){


    }


}
