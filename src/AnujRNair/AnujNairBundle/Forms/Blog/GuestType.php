<?php

namespace AnujRNair\AnujNairBundle\Forms\Blog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label'      => 'Name',
                'label_attr' => [
                    'class' => 'sr-only'
                ],
                'required'   => true,
                'attr'       => [
                    'placeholder' => 'Name',
                    'maxlength'   => 100
                ]
            ]);
    }

    /**
     * Returns the name of this type.
     * @return string The name of this type
     */
    public function getName()
    {
        return 'anujnair_guest_form';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AnujRNair\AnujNairBundle\Entity\Guest',
        ]);
    }

}
