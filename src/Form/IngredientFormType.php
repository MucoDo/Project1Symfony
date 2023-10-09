<?php

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use App\Form\IngredientAutocompleteField;
use Symfony\Component\Form\FormBuilderInterface;

class IngredientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                   ->add('ingredient', IngredientAutocompleteField::class)
        ;
    }
}