<?php namespace OctoDevel\OctoCase\Components;

use Cms\Classes\ComponentBase;
use OctoDevel\OctoCase\Models\Item as OctoCaseItem;

class Item extends ComponentBase
{
    public $item;

    public function componentDetails()
    {
        return [
            'name'        => 'OctoCase Item',
            'description' => 'Displays a octocase item on the page.'
        ];
    }

    public function defineProperties()
    {
        return [
            'paramId' => [
                'description' => 'The URL route parameter used for looking up the item by its slug.',
                'title'       => 'Slug param name',
                'default'     => 'slug',
                'type'        => 'string'
            ]
        ];
    }

    public function onRun()
    {
        $this->item = $this->page['octocaseItem'] = $this->loadItem();
    }

    protected function loadItem()
    {
        $slug = $this->param($this->property('paramId'));
        return OctoCaseItem::isPublished()->where('slug', '=', $slug)->first();
    }
}