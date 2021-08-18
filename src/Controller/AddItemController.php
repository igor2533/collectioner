<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Images;
use App\Form\AddItemFormType;
use App\Form\TagType;
use App\Repository\ItemRepository;
use ContainerBIxhQvB\getSpeicher210Cloudinary_ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;
use Symfony\Component\Security\Core\User\UserInterface;
use Speicher210\CloudinaryBundle\Cloudinary\Uploader;
class AddItemController extends AbstractController
{

    #[Route('/add_item', name: 'app_add_item')]
    public function add_item(Request $request,UserInterface $user,ItemRepository $itemRepository): Response
    {
        $item = new Item();
        $form = $this->createForm(AddItemFormType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach($images as $image){
                $my_generate = random_int(100000000, 900000000);
                Uploader::upload($image,[
                  'public_id' => $my_generate,
                   'version' => '99999999999999'
                ]);
                $link_cloud = 'https://res.cloudinary.com/karasika/image/upload/'.strval($my_generate).".".$image->getClientOriginalExtension();
                $img = new Images();
                $img->setName($link_cloud);
                $item->addImage($img);
            }
            $slugify = new Slugify();


            $new_slug = $slugify->slugify($item->getTitle()."_".$item->getId());
            $i = 1;
         do
         {
            $find_items_by_slug = $itemRepository->findBy(array(

                'slug' => $new_slug,
            ));
             if(count($find_items_by_slug) === 0){break;}
             $i++;


                 $new_slug = $slugify->slugify($item->getTitle() . "_" . $i);


         }
         while(count($find_items_by_slug) !== 0);



            $item->setSlug($new_slug);
            $item->setDateCreated(new \DateTime());
            $item->setAuthor($this->getUser());
            $item->setStatus(1);
            $item->setLikes(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();
            return $this->redirectToRoute('index');
        }
        return $this->render('item/add.html.twig', [
            'addItemForm' => $form->createView(),

        ]);
    }



}
