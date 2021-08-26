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
use App\Form\CommentFormType;

use App\Form\EditItemFormType;
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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\isGranted;



//use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Core\User\UserInterface;

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













}