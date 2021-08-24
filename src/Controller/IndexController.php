<?php

namespace App\Controller;

//use App\Entity\Category;
//use App\Entity\User;
//use App\Entity\Item;
//use App\Entity\Tag;

use App\Entity\Collection;
use App\Entity\Comments;
use App\Entity\Images;
use App\Entity\Item;
use App\Entity\Tag;
use App\Form\AddCollectionsFormType;
use App\Form\CommentFormType;

use App\Repository\CollectionsRepository;
use App\Repository\ImagesRepository;
use App\Repository\ItemRepository;

use App\Repository\CategoryRepository;
use App\Form\UpdateImagesFormType;
//use App\Controller\SecurityController;
//use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use PhpParser\Node\Scalar\MagicConst\File;
use Speicher210\CloudinaryBundle\Cloudinary\Uploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


//use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Core\User\UserInterface;

class IndexController extends AbstractController
{

    public function index(CollectionsRepository $collectionsRepository,CategoryRepository $categoryRepository,ItemRepository $itemRepository):Response {
        $collections = $collectionsRepository->findAll();

        $items = $itemRepository->findAll();
        return $this->render('home.html.twig',
        ['collections' => $collections,
            'items'=> $items
            ]);

    }
    public function collection(ItemRepository $itemRepository,CollectionsRepository $collectionsRepository,string $slug, Request $request):Response {

        $collection = $collectionsRepository->findOneBy(array(

            'slug' => $request->get('slug'),
        ));

        $items = $itemRepository->findBy(array(
            'collection' => $collection->getId()
        ));

        $count_items = count($items);

        return $this->render('collection-page.html.twig',
            ['collection' => $collection,
                'items' => $items,
                'count_items' => $count_items
                ]);

    }


    public function show(ItemRepository $itemRepository,string $slug, Request $request,UserInterface $user):Response {

        $item = $itemRepository->findOneBy(array(

            'slug' => $request->get('slug'),
        ));



        $comment = new Comments();
        $form = $this->createForm(CommentFormType::class, $comment);



        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // $comment = $form->get('images')->getData();

            // $item->setAuthor($this->getUser());
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


        $user_collections = $collectionsRepository->findBy(array(

            'author' => $request->get('author'),
        ));
        $current_user = $userRepository->findOneBy(
          array(
              'id' => $request->get('author')
          )
        );

        return $this->render('user.html.twig',
            ['user_collections' => $user_collections,
                'current_user' => $current_user


            ]);

    }


     public function remove_image(ImagesRepository $imagesRepository,Request $request) {

         $entityManager = $this->getDoctrine()->getManager();
         $image = $entityManager->getRepository(Images::class)->find($request->get('image_id'));
         if (!$image) {
             throw $this->createNotFoundException(
                 'No product found for id '.$request->get('image_id')
             );
         }

         $entityManager->remove($image);
         $entityManager->flush();

         return $this->redirectToRoute('edit_gallery');

     }

    public function remove_item(ItemRepository $itemRepository,Request $request) {

        $entityManager = $this->getDoctrine()->getManager();
        $item = $entityManager->getRepository(Item::class)->find($request->get('item_id'));
        if (!$item) {
            throw $this->createNotFoundException(
                'No item found for id '.$request->get('item_id')
            );
        }

        $entityManager->remove($item);
        $entityManager->flush();

        return $this->redirectToRoute('index');

    }

    public function remove_collection(CollectionsRepository $collectionsRepository,Request $request) {

        $entityManager = $this->getDoctrine()->getManager();
        $collection = $entityManager->getRepository(Collection::class)->find($request->get('collection_id'));
        if (!$collection) {
            throw $this->createNotFoundException(
                'No collection found for id '.$request->get('collection_id')
            );
        }

        $entityManager->remove($collection);
        $entityManager->flush();

        return $this->redirectToRoute('index');

    }


    public function edit(ItemRepository $itemRepository ,Request $request) {

        $em = $this->getDoctrine()->getManager();
        $item = $itemRepository->findOneBy(array(
            'id' => $request->get('id')
        ));

        if (!$item) {
            throw $this->createNotFoundException(
                'No news found for id ' . $request->get('id')
            );
        }

        $form = $this->createFormBuilder($item)
            ->add('title')
          ->add('description', CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#ffffff',
                )))
            ->add('tag')
           ->add('year')
          ->add('images', CollectionType::class, [
              'entry_type' => UpdateImagesFormType::class,
              'allow_delete' => true,
              'allow_add' => true,
              'delete_empty' => true
           ])

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            $images = $form->get('images')->getData();
//            foreach($images as $image){
//                $my_generate = random_int(100000000, 900000000);
//                Uploader::upload($image,[
//                    'public_id' => $my_generate,
//                    'version' => '99999999999999'
//                ]);
//                $link_cloud = 'https://res.cloudinary.com/karasika/image/upload/'.strval($my_generate).".".$image->getClientOriginalExtension();
//                $img = new Images();
//                $img->setName($link_cloud);
//                $item->addImage($img);
//            }

            $em->flush();
            //return new Response('News updated successfully');
        }

        //$build['form'] = $form->createView();

        //return $this->render('/item/update.html.twig', $build);


        return $this->render('/item/update_item.html.twig',
            [
                'updateItemForm' => $form->createView(),
                'item' => $item


            ]);
    }













}