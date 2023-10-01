<?php

namespace App\Form;

use App\Entity\Projects;
use App\Entity\Technology;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('url')
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'multiple' => false
            ])
            ->remove('developper')
            ->add('technology', EntityType::class, [
                'expanded' => true,
                'multiple' => true,
                'required' => true,
                'class' => Technology::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projects::class,
        ]);
    }
}
