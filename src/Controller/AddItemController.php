<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Tag;
use App\Entity\Images;

use App\Form\AddItemFormType;
use App\Form\TagType;
//use Cloudinary\Cloudinary;
use ContainerBIxhQvB\getSpeicher210Cloudinary_ApiService;
use Speicher210\CloudinaryBundle\Cloudinary\Cloudinary;
use Speicher210\CloudinaryBundle\Command\UploadCommand;
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
use Speicher210\CloudinaryBundle\Cloudinary\Api;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AddItemController extends AbstractController
{







    #[Route('/add_item', name: 'app_add_item')]
    public function add_item(Request $request,UserInterface $user): Response
    {


     $item = new Item();
       $tag = new Tag();



        $form = $this->createForm(AddItemFormType::class, $item);
        $form_tag = $this->createForm(TagType::class,$tag);


        $form->handleRequest($request);
        $form_tag->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();

            foreach($images as $image){
                // On génère un nouveau nom de fichier


                //$fichier =  $my_generate.'.'.$image->guessExtension();

                // On copie le fichier dans le dossier uploads
//                $image->move(
//                    $this->getParameter('images_directory'),
//                    $fichier
//                );

                $my_generate = random_int(100000000, 900000000);
                Uploader::upload($image,[
                  'public_id' => $my_generate,
                   'version' => '99999999999999'
               ]);
            $link_cloud = 'https://res.cloudinary.com/karasika/image/upload/'.strval($my_generate).".".$image->getClientOriginalExtension();






                    // On crée l'image dans la base de données
                $img = new Images();
                $img->setName($link_cloud);
                $item->addImage($img);
            }



            // encode the plain password
           // $item->setRoles(array('ROLE_USER'));
            $slugify = new Slugify();

            //$slugify = $slugify."_".strval($item->getId());
              $item->setSlug($slugify->slugify($item->getTitle()."_".$item->getId()));
            //$dateImmutable = \DateTime::createFromFormat('Y-m-d H:i:s', strtotime('now')); # also tried using \DateTimeImmutable

            $item->setDateCreated(new \DateTime());
          $item->setAuthor($this->getUser());


            $item->setStatus(1);
            $item->setLikes(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);


            $entityManager->flush();
            // do anything else you need here, like send an email



            return $this->redirectToRoute('index');
        }








        return $this->render('item/add.html.twig', [
            'addItemForm' => $form->createView(),
            'addTagForm' => $form_tag->createView()
        ]);
    }















    public function upload($file, $options = []){


    }


}
