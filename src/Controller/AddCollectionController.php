<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Images;
use App\Form\AddCollectionsFormType;
use App\Form\AddItemFormType;
use App\Entity\Collections;
use App\Form\TagType;
use App\Repository\ItemRepository;
use App\Repository\CollectionsRepository;

use ContainerBIxhQvB\getSpeicher210Cloudinary_ApiService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;
use Symfony\Component\Security\Core\User\UserInterface;
use Speicher210\CloudinaryBundle\Cloudinary\Uploader;
use Symfony\Component\Validator\Constraints\Collection;

class AddCollectionController extends AbstractController
{

    #[Route('/collection_create', name: 'create_collection')]
    public function add_collection(Request $request,ItemRepository $itemRepository,CollectionsRepository $collectionsRepository): Response
    {
        $collection = new Collections();
        $item = new Item();








        $form = $this->createForm(AddCollectionsFormType::class, $collection);
        $form_item = $this->createForm(AddItemFormType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {




            $image = $form->get('image')->getData();



                $my_generate = random_int(100000000, 900000000);
                Uploader::upload($image,[
                    'public_id' => $my_generate,
                    'version' => '99999999999999'
                ]);
                $link_cloud = 'https://res.cloudinary.com/karasika/image/upload/'.strval($my_generate).".".$image->getClientOriginalExtension();


                $collection->setImage($link_cloud);



            $slugify_new = new Slugify();


            $slug_collection = $slugify_new->slugify($collection->getTitle());
            $i = 1;
            do
            {
                $find_collections_by_slug = $collectionsRepository->findBy(array(

                    'slug' => $slug_collection,
                ));
                if(count($find_collections_by_slug) === 0){break;}
                $i++;


                $slug_collection = $slugify_new->slugify($collection->getTitle() . "_" . $i);


            }
            while(count($find_collections_by_slug) !== 0);


            $collection->setSlug($slug_collection);

            $collection->setDateCreated(new \DateTime());

            $collection->setAuthor($this->getUser());


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($collection);

            //$entityManager->persist($item);


            //$item = $entityManager->getRepository($itemRepository);

//          print_r($item);
//          die();
            $entityManager->flush();



            foreach($collection->getItems() as $item){

                $images =  $form['items']['undefined']['images']->getData();

//                print_r($images);
//                die();

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
            $item->setCollection($collection);
            $item->setStatus(1);
                $item->setLikes(0);
            $item->setAuthor($this->getUser());
                $entityManager->persist($item);
                $entityManager->flush();
            }






            //$collection->getItems();



//
//
//
//            $item = $itemRepository->findBy(array(
//                id =>  $collection->getItems()
//            ));
//
//            $item->setCollection($collection->getId());





           // return $this->redirectToRoute('index');
            }



        return $this->render('item/add_collection.html.twig', [
            'addCollectionForm' => $form->createView(),

        ]);
    }



}
