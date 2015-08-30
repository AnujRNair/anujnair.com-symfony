<?php

namespace AnujRNair\AnujNairBundle\Forms\Blog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Name',
                'label_attr' => [
                    'class' => 'sr-only'
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Name',
                    'maxlength' => 100
                ]
            ])
            ->add('comment', 'textarea', [
                'label' => 'Comment',
                'label_attr' => [
                    'class' => 'sr-only'
                ],
                'required' => true,
                'attr'  => [
                    'class' => 'form-control',
                    'placeholder' => 'Write comment here',
                    'maxlength' => 8000,
                    'rows' => 5
                ]
            ])
            ->add('save', 'submit', [
                'label' => 'Comment',
                'attr' => [
                    'class' => 'btn btn-success pull-right'
                ]
            ]);
    }

    /**
     * Returns the name of this type.
     * @return string The name of this type
     */
    public function getName()
    {
        return 'anujnair_comment_form';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AnujRNair\AnujNairBundle\Entity\Comment',
        ]);
    }

}
