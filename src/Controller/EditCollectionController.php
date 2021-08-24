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


class EditCollectionController extends AbstractController
{

    #[Route('/edit_collection', name: 'edit_collection')]
    public function edit_collection(Request $request, CollectionsRepository $collectionsRepository,ItemRepository $itemRepository): Response
    {

        $id = $request->get('id');
        $collection = $collectionsRepository->findOneBy(array(
            'id' => $id
        ));


        $items = $itemRepository->findBy(array(
            'collection' => $collection->getId()
        ));




      $originalItems = new ArrayCollection();

        foreach ($collection->getItems() as $item) {




            $originalItems->add($item);

        }
        $editForm = $this->createForm(AddCollectionsFormType::class, $collection);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {




            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($collection);
            $entityManager->flush();





            foreach($collection->getItems() as $item) {



                if($item->getImages() == null) {
                    $images = $editForm['items']['undefined']['images']->getData();

//                print_r($images);
//                die();

                    foreach ($images as $image) {
                        $my_generate = random_int(100000000, 900000000);
                        Uploader::upload($image, [
                            'public_id' => $my_generate,
                            'version' => '99999999999999'
                        ]);
                        $link_cloud = 'https://res.cloudinary.com/karasika/image/upload/' . strval($my_generate) . "." . $image->getClientOriginalExtension();
                        $img = new Images();
                        $img->setName($link_cloud);
                        $item->addImage($img);
                    }

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


                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($item);
                $entityManager->flush();



            }




            }







        return $this->render('item/update.html.twig', [
            'updateForm' => $editForm->createView(),
            'collection' => $collection,

            'items' => $items

        ]);
    }



}
