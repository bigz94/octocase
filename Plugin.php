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
            'name'        => 'OctoCase',
            'description' => 'Provides a octocase plugin, can be used to show products, services or until like a photos gallery.',
            'author'      => 'Fabricio Pereira Rabelo',
            'icon'        => 'icon-image'
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
                'icon'        => 'icon-image',
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

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register()
    {
        /*
         * Register the image tag processing callback
         */

        TagProcessor::instance()->registerCallback(function($input, $preview){
            if (!$preview)
                return $input;

            return preg_replace('|\<img alt="([0-9]+)" src="image"([^>]*)\/>|m',
                '<span class="image-placeholder" data-index="$1">
                    <span class="dropzone">
                        <span class="label">Click or drop an image...</span>
                        <span class="indicator"></span>
                    </span>
                    <input type="file" class="file" name="image[$1]"/>
                    <input type="file" class="trigger"/>
                </span>',
            $input);
        });
    }
}