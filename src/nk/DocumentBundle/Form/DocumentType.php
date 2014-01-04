<?php

namespace nk\DocumentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
    private $metadata;

    public function __construct(array $metadata = null)
    {
        $this->metadata = $metadata;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
                'label' => 'Type',
                'choices' => array(
                    'Annale' => 'Annale',
                    'Cour' => 'Cour',
                    'TD' => 'TD',
                    'TP' => 'TP',
                )
            ))
            ->add('class', 'text', array(
                'label' => 'Promo',
                'attr' => array(
                    'placeholder' => 'E1, E2, E3E, ...',
                    'data-autocomplete' => implode(',', $this->metadata['classes']),
                )
            ))
            ->add('field', 'text', array(
                'label' => 'Matière',
                'attr' => array(
                    'placeholder' => 'Physique, Electronique, ...',
                    'data-autocomplete' => implode(',', $this->metadata['fields']),
                )
            ))
            ->add('teacher', 'text', array(
                'label' => 'Professeur',
                'attr' => array(
                    'placeholder' => 'Douay, Exertier, ...',
                    'data-autocomplete' => implode(',', $this->metadata['teachers']),
                )
            ))
            ->add('subject', 'text', array(
                'label' => 'Titre',
                'attr' => array(
                    'placeholder' => 'Description du contenu.',
                )
            ))
            ->add('year', 'text', array(
                'label' => 'Année',
                'attr' => array(
                    'placeholder' => '2014, 2014-2015',
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'nk\DocumentBundle\Entity\Document'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'nk_documentbundle_document';
    }
}
