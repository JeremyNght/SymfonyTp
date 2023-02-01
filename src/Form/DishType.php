<?php

namespace App\Form;

use App\Entity\Dish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class DishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, ['required' => true,])
            ->add('Calories', ChoiceType::class, [
                'choices' => $this->_availableCalories(),
                'placeholder' => 'Choisir un nombre de calories',
            ])
            ->add('Price', NumberType::class, [
                'required' => true,
                'constraints' => [
                    new Type(['type' => 'numeric']),
                ],
            ])
            ->add('Image', TextType::class, [
                'required' => false, 'empty_data' => 'https://via.placeholder.com/360x225',
            ])
            ->add('Description', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'max' => 100,
                        'minMessage' => 'La description doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'La description ne peut pas comporter plus de {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('Sticky')
            ->add('User')
            ->add('category', EntityType::class, ['required' => true, 'class' => 'App\Entity\Category'])
            ->add('allergen')
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dish::class,
        ]);
    }

    private function _availableCalories(): array
    {
        $calories = array ();
        for ( $i = 10 ; $i <= 300 ; $i += 10 )
            $calories [ $i ]= $i ;
        return $calories ;
    }
}