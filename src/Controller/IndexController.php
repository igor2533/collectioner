<?php

namespace App\Controller;

//use App\Entity\Category;
//use App\Entity\User;
//use App\Entity\Item;
//use App\Entity\Tag;

use App\Entity\Collection;
use App\Entity\Collections;
use App\Entity\Comments;
use App\Entity\Images;
use App\Entity\Item;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\AddCollectionsFormType;
use App\Form\AddItemFormType;
use App\Form\CommentFormType;

use App\Form\EditCollectionsFormType;
use App\Form\EditGalleryFormType;
use App\Form\EditItemFormType;
use App\Repository\CollectionsRepository;
use App\Repository\ImagesRepository;
use App\Repository\ItemRepository;
use App\Repository\CategoryRepository;
use App\Form\UpdateImagesFormType;

use App\Repository\UserRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use PhpParser\Node\Scalar\MagicConst\File;
use Speicher210\CloudinaryBundle\Cloudinary\Uploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class IndexController extends AbstractController
{



    public $em;

    public function em() {
        $em = $this->getDoctrine()->getManager();
        return $em;
    }



    public function find_object_one($request,$repository,$key,$get_key) {

        $object = $repository->findOneBy(array(
        $key => $request->get($get_key),
        ));
        return $object;
    }

    public function find_objects($request,$repository,$key,$get_key) {

        $objects = $repository->findBy(array(
            $key => $request->get($get_key),
        ));
        return $objects;
    }

    public function uploader_image($image) {

        $my_generate = random_int(100000000, 900000000);
        Uploader::upload($image,[
            'public_id' => $my_generate,
            'version' => '99999999999999'
        ]);
        $link_cloud = 'https://res.cloudinary.com/karasika/image/upload/'.strval($my_generate).".".$image->getClientOriginalExtension();

        return $link_cloud;

    }
    public function addSlugInObj($request,$object, $repository) {

        $slugify = new Slugify();
        $new_slug = $slugify->slugify($object->getTitle());
        $i = 1;
        do
        {
            $find_objects_by_slug = $repository->findBy(array(
                'slug' => $new_slug,
            ));
            if(count($find_objects_by_slug) === 0){break;}
            $i++;
            $new_slug = $slugify->slugify($object->getTitle() . "_" . $i);
        }
        while(count($find_objects_by_slug) !== 0);


        return $new_slug;





    }


    public function index(CollectionsRepository $collectionsRepository,CategoryRepository $categoryRepository,ItemRepository $itemRepository):Response {
        $collections = $collectionsRepository->findAll();

        $items = $itemRepository->findAll();
        return $this->render('home.html.twig',
        ['collections' => $collections,
            'items'=> $items
            ]);

    }


    public function collection(ItemRepository $itemRepository,CollectionsRepository $collectionsRepository,string $slug, Request $request):Response {

        $collection = $this->find_object_one($request, $collectionsRepository,'slug','slug');
        $items =$collection->getItems();
      return $this->render('collection-page.html.twig',
            ['collection' => $collection,
                'items' => $items,
                'count_items' => count($items)
                ]);

    }


    public function show(ItemRepository $itemRepository,string $slug, Request $request,UserInterface $user):Response {

        $item = $this->find_object_one($request, $itemRepository,'slug','slug');
        $comment = new Comments();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setItem($item);
            $comment->setDateCreated(new \DateTime());
            $comment->setAuthor($this->getUser());
            //$comment->setDescription();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();


        }

        return $this->render('view.html.twig',
            ['item' => $item,
                'commentForm' => $form->createView(),

               ]);

    }

    public function user(CollectionsRepository $collectionsRepository,UserRepository $userRepository,UserInterface $user, Request $request){


        $user_collections = $this->find_object_one($request, $collectionsRepository,'author','author');
        $current_user = $this->find_object_one($request, $userRepository,'id','author');

        return $this->render('user.html.twig',
            ['user_collections' => $user_collections,
                'current_user' => $current_user


            ]);

    }


     public function remove_image(ImagesRepository $imagesRepository,Request $request) {

         $repository = $this->em()->getRepository(Images::class);
         $this->remove_object($request,$repository);
         return $this->redirectToRoute('index');

     }

     public function check_security($object) {

         if ($object->getAuthor() !== $this->getUser()) {
             return $this->denyAccessUnlessGranted('ROLE_ADMIN');
         }

     }



     public function remove_object($request,$repository) {

        $object = $repository->find($request->get('id'));
        $this->check_security($object);
        if (!$object) {
             if ($object->getAuthor() !== $this->getUser()) {
                 $this->denyAccessUnlessGranted('ROLE_ADMIN');
             }
             throw $this->createNotFoundException(
                 'No object found for id '.$request->get('id')
             );
         }
         $this->em()->remove($object);
         $this->em()->flush();
         return $object;
     }

    public function remove_item(Request $request) {

        $repository = $this->em()->getRepository(Item::class);
        $this->remove_object($request,$repository);
        return $this->redirectToRoute('index');

    }

    public function remove_collection(Request $request) {


        $repository = $this->em()->getRepository(Collections::class);
        $this->remove_object($request,$repository);
        return $this->redirectToRoute('index');

    }



    public function edit(ItemRepository $itemRepository ,Request $request,UserInterface $user) {


        $em = $this->getDoctrine()->getManager();
       $item = $this->find_object_one($request, $itemRepository,'id','id');

        $this->check_security($item);

        if (!$item) {
            throw $this->createNotFoundException(
                'No news found for id ' . $request->get('id')
            );
        }

        $form = $this->createForm(EditItemFormType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }
        return $this->render('/item/update_item.html.twig',
            [
                'updateItemForm' => $form->createView(),
                'item' => $item

            ]);
    }



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
           $link_cloud = $this->uploader_image($image);
            $collection->setImage($link_cloud);
            $new_slug = $this->addSlugInObj($request,$collection,$collectionsRepository);
            $collection->setSlug($new_slug);
            $collection->setDateCreated(new \DateTime());
            $collection->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($collection);
            $entityManager->flush();
            foreach($collection->getItems() as $item){
                $images =  $form['items']['undefined']['images']->getData();
                foreach($images as $image){

                   $link_cloud = $this->uploader_image($image);
                    $img = new Images();
                    $img->setName($link_cloud);
                    $img->setAuthor($this->getUser());
                    $item->addImage($img);
                }

                $new_slug = $this->addSlugInObj($request, $item, $itemRepository);

                $item->setSlug($new_slug);
                $item->setDateCreated(new \DateTime());
                $item->setCollection($collection);
                $item->setStatus(1);
                $item->setLikes(0);
                $item->setAuthor($this->getUser());
                $entityManager->persist($item);
                $entityManager->flush();
            }
        }
        return $this->render('item/add_collection.html.twig', [
            'addCollectionForm' => $form->createView(),
        ]);
    }



    #[Route('/edit_collection', name: 'edit_collection')]
    public function edit_collection(Request $request, CollectionsRepository $collectionsRepository,ItemRepository $itemRepository): Response
    {

        $collection = $this->find_object_one($request,$collectionsRepository,'id','id');
        $items = $collection->getItems();
        $originalItems = new ArrayCollection();
        foreach ($items as $item) {
            $originalItems->add($item);
        }
        $editForm = $this->createForm(EditCollectionsFormType::class, $collection);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($collection);
            $entityManager->flush();
            foreach($collection->getItems() as $item) {

                if($item->getImages() == null) {
                    $images = $editForm['items']['undefined']['images']->getData();
                    foreach ($images as $image) {
                       $link_cloud = $this->uploader_image($image);
                        $img = new Images();
                        $img->setName($link_cloud);
                        $img->setAuthor($this->getUser());
                        $item->addImage($img);
                    }
                }
               $new_slug = $this->addSlugInObj($request, $item, $itemRepository);
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



    #[Route('/add_item', name: 'app_add_item')]
    public function add_item(Request $request,UserInterface $user,ItemRepository $itemRepository,CollectionsRepository $collectionsRepository): Response
    {
        $item = new Item();
        $form = $this->createForm(AddItemFormType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach($images as $image){
              $link_cloud = $this->uploader_image($image);
                $img = new Images();
                $img->setName($link_cloud);
                $img->setAuthor($this->getUser());
                $item->addImage($img);
            }
            $collection = $this->find_object_one($request,$collectionsRepository,'id','id_collection');
            $new_slug = $this->addSlugInObj($request,$item,$itemRepository);
            $item->setCollection($collection);
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


    #[Route('/edit_gallery', name: 'edit_gallery')]
    public function edit_gallery(Request $request, ImagesRepository $imagesRepository,ItemRepository $itemRepository): Response
    {
        $item = $this->find_object_one($request,$itemRepository,'id','id');
        $images = $this->find_objects($request,$imagesRepository,'item','id');
        $editGalleryForm = $this->createForm(EditGalleryFormType::class, $item);
        $editGalleryForm->handleRequest($request);
        return $this->render('/item/edit_gallery.html.twig', [
            'editGallery' => $editGalleryForm->createView(),
            'item' => $item,
            'images' => $images


        ]);
    }





}