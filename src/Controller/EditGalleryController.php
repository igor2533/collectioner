<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Images;
use App\Form\AddCollectionsFormType;
use App\Form\AddItemFormType;
use App\Entity\Collections;



use App\Form\EditGalleryFormType;
use App\Form\TagType;
use App\Repository\ImagesRepository;
use App\Repository\ItemRepository;
use App\Repository\CollectionsRepository;
use App\Form\AddImageFormType;
use ContainerBIxhQvB\getSpeicher210Cloudinary_ApiService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;
use Symfony\Component\Security\Core\User\UserInterface;
use Speicher210\CloudinaryBundle\Cloudinary\Uploader;


class EditGalleryController extends AbstractController
{

    #[Route('/edit_gallery', name: 'edit_gallery')]
    public function edit_gallery(Request $request, ImagesRepository $imagesRepository,ItemRepository $itemRepository): Response
    {


$item = $itemRepository->findOneBy(array(
    'id' => $request->get('id')
));


$images = $imagesRepository->findBy(array(
    'item' => $request->get('id')
));

        $editGalleryForm = $this->createForm(EditGalleryFormType::class, $item);

        $editGalleryForm->handleRequest($request);






        return $this->render('/item/edit_gallery.html.twig', [
            'editGallery' => $editGalleryForm->createView(),
           'item' => $item,
            'images' => $images


        ]);
    }



}
