<?php namespace OctoDevel\OctoCase;

use Backend;
use Controller;
use System\Classes\PluginBase;
use OctoDevel\OctoCase\Classes\TagProcessor;

class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Octo Case',
            'description' => 'Provides a octocase plugin, can be used to show products, services or until like a photos gallery.',
            'author'      => 'Fabricio Pereira Rabelo',
            'icon'        => 'icon-cubes'
        ];
    }

    public function registerComponents()
    {
        return [
            'OctoDevel\OctoCase\Components\Item' => 'octocaseItem',
            'OctoDevel\OctoCase\Components\Items' => 'octocaseItems',
            'OctoDevel\OctoCase\Components\Categories' => 'octocaseCategories',
            'OctoDevel\OctoCase\Components\Category' => 'octocaseCategory'
        ];
    }

    public function registerNavigation()
    {
        return [
            'octocase' => [
                'label'       => 'Octo Case',
                'url'         => Backend::url('octodevel/octocase/items'),
                'icon'        => 'icon-cubes',
                'permissions' => ['octocase.*'],
                'order'       => 500,

                'sideMenu' => [
                    'items' => [
                        'label'       => 'Items',
                        'icon'        => 'icon-file-text-o',
                        'url'         => Backend::url('octodevel/octocase/items'),
                        'permissions' => ['octocase.access_items'],
                    ],
                    'categories' => [
                        'label'       => 'Categories',
                        'icon'        => 'icon-list',
                        'url'         => Backend::url('octodevel/octocase/categories'),
                        'permissions' => ['octocase.access_categories'],
                    ],
                ]

            ]
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'OctoDevel\OctoCase\FormWidgets\Trumbowyg' => [
                'label' => 'Trumbowyg',
                'alias' => 'trumbowyg'
            ]
        ];
    }

}