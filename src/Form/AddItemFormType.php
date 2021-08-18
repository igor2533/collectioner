<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Item;
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
            ->add('title')->add('description', CKEditorType::class, array(
        'config' => array(
            'uiColor' => '#ffffff',
          )))

            ->add('category')
//            ->add('image', FileType::class, [
//                'label' => 'Главное изображение новости',
//                'multiple' => false,
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
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])



           ->add('tag')
//   ->add('image', ChoiceType::class, [
//       'multiple'=>true,
//         'choices' => [
//             '0' => 'first',
//             '1' => 'second',
//         ],
//     ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])

        ;





    }




    public function configureOptions(OptionsResolver $resolver):void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,


        ]);
    }
}
