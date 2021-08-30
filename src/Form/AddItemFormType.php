<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Item;
use App\Entity\Collections;
use App\Entity\Images;
use App\Entity\Tag;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Types\ArrayType;
use Doctrine\ORM\Mapping\Builder\AssociationBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class AddItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', CKEditorType::class, array(
                'attr' => array('class' => 'form-control'),
        'config' => array(
            'uiColor' => '#ffffff',
          )))


//            ->add('image', FileType::class, [
//                'label' => 'Главное изображение новости',
//                'multiple' => fals
////                'mapped' => false,
////                'required' => false,
////                'constraints' => [
////                    new File([
////                        'maxSize' => '2024k',
////                        'mimeTypes' => [
////                            'image/*',
////                        ],
////                        'mimeTypesMessage' => 'Please upload a valid jpg document',
////                    ])
////                ],
//            ])


            ->add('images', FileType::class,[
                'attr' => array('class'=>'form_images'),
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])

            ->add('year', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])


           ->add('tag')
//   ->add('image', ChoiceType::class, [
//       'multiple'=>true,
//         'choices' => [
//             '0' => 'first',
//             '1' => 'second',
//         ],
//     ])


        ;





    }




    public function configureOptions(OptionsResolver $resolver):void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,


        ]);
    }
}
