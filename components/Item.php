<?php namespace OctoDevel\OctoCase\Components;

use Cms\Classes\ComponentBase;
use OctoDevel\OctoCase\Models\Item as OctoCaseItem;

class Item extends ComponentBase
{
    public $item;

    public function componentDetails()
    {
        return [
            'name'        => 'OctoCase Single Item',
            'description' => 'Displays an octocase item on the page.'
        ];
    }

    public function defineProperties()
    {
        return [
            'idParam' => [
                'title'       => 'Slug param name',
                'description' => 'The URL route parameter used for looking up the item by its slug.',
                'default'     => ':slug',
                'type'        => 'string'
            ],
        ];
    }

    public function onRun()
    {
        $this->item = $this->page['item'] = $this->loadItem();
    }

    protected function loadItem()
    {
        $slug = $this->propertyOrParam('idParam');
        return OctoCaseItem::isPublished()->where('slug', '=', $slug)->first();
    }
}