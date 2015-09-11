<?php

namespace AnujRNair\AnujNairBundle\Forms\Blog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CommentType
 * @package AnujRNair\AnujNairBundle\Forms\Blog
 */
class CommentType extends AbstractType
{

    /**
     * @var string The url to post to
     */
    private $actionUrl;

    /**
     * @param string $actionUrl
     */
    public function __construct($actionUrl)
    {
        $this->actionUrl = $actionUrl;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->actionUrl)
            ->add('guest', new GuestType())
            ->add('comment', 'textarea', [
                'label'      => 'Comment',
                'label_attr' => [
                    'class' => 'sr-only'
                ],
                'required'   => true,
                'attr'       => [
                    'placeholder' => 'Write comment here',
                    'maxlength'   => 8000,
                    'rows'        => 5
                ]
            ])
            ->add('save', 'submit', [
                'label' => 'Comment',
                'attr'  => [
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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'AnujRNair\AnujNairBundle\Entity\Comment',
            'cascade_validation' => true
        ]);
    }

}
