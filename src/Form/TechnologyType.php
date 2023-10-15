<?php

namespace App\Form;

use App\Entity\Technology;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TechnologyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name')
                ->remove('projects');       
                if($options['is_adding_form']){
                    $builder->add('logo', FileType::class,[
                        'mapped' => false,
                        'required' => true,
                        'multiple' => false
                    ]);
                }
                if($options['is_updating_form']){
                    $builder->add('logo', FileType::class,[
                        'mapped' => false,
                        'required' => false,
                        'multiple' => false
                    ]);
                }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Technology::class,
            'is_adding_form' => false,
            'is_updating_form' => false,
        ]);
    }
}
