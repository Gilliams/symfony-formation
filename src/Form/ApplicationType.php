<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;


class ApplicationType extends AbstractType
{

    /**
     * Permet d'avoir la configuration de base d'un champ !
     * @param  [string] $label       [description]
     * @param  [string] $placeholder [description]
     * @param  [array] $options [description]
     * @return [array]              [description]
     */
    protected function getConfiguration($label, $placeholder, $options = []){
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }

}
