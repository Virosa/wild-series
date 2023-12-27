<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;


class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('rate', IntegerType::class, [
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'max' => 10,
                        'minMessage' => 'La note doit être au minimum {{ limit }}',
                        'maxMessage' => 'La note doit être au maximum {{ limit }}',
                    ]),
                ],
                'label' => 'Note (1 à 10)'
            ])
            ->add('comment', TextType::class, [
                'label' => 'Commentaire'
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
