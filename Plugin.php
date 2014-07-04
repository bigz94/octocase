<?php namespace OctoDevel\OctoSlider;

use Backend;
use Controller;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Octo Slider',
            'description' => 'Create slider in your website using Camera Slider jQuery Plugin.',
            'author'      => 'Octo Devel',
            'icon'        => 'icon-picture-o'
        ];
    }

    public function registerComponents()
    {
        return [
            'OctoDevel\OctoSlider\Components\Items' => 'octosliderItem',
        ];
    }

    public function registerNavigation()
    {
        return [
            'octoslider' => [
                'label'       => 'Octo Slider',
                'url'         => Backend::url('octodevel/octoslider/items'),
                'icon'        => 'icon-picture-o',
                'permissions' => ['octoslider.*'],
                'order'       => 500,

                'sideMenu' => [
                    'items' => [
                        'label'       => 'Slide Items',
                        'icon'        => 'icon-th-large',
                        'url'         => Backend::url('octodevel/octoslider/items'),
                        'permissions' => ['octoslider.access_items'],
                    ]
                ]
            ]
        ];
    }

}