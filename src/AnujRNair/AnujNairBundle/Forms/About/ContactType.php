<?php

namespace AnujRNair\AnujNairBundle\Forms\About;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ContactType
 * @package AnujRNair\AnujNairBundle\Forms\Blog
 */
class ContactType extends AbstractType
{

    /**
     * @var string
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
            ])
            ->add('email', 'email', [
                'label'      => 'Email',
                'label_attr' => [
                    'class' => 'sr-only'
                ],
                'required'   => true,
                'attr'       => [
                    'placeholder' => 'Email',
                    'maxlength'   => 200
                ]
            ])
            ->add('subject', 'text', [
                'label'      => 'Subject',
                'label_attr' => [
                    'class' => 'sr-only'
                ],
                'required'   => true,
                'attr'       => [
                    'placeholder' => 'Subject',
                    'maxlength'   => 200
                ]
            ])
            ->add('contents', 'textarea', [
                'label'      => 'Contents',
                'label_attr' => [
                    'class' => 'sr-only'
                ],
                'required'   => true,
                'attr'       => [
                    'placeholder' => 'Write email here',
                    'maxlength'   => 2000,
                    'rows'        => 4
                ]
            ])
            ->add('send', 'submit', [
                'label' => 'Send',
                'icon' => 'icon-paper-plane',
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
        return 'anujnair_contact_form';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AnujRNair\AnujNairBundle\Entity\Form\Contact'
        ]);
    }

}
