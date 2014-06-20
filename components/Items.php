<?php namespace OctoDevel\OctoCase\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\CmsPropertyHelper;
use OctoDevel\OctoCase\Models\Item as OctoCaseItem;
use Request;
use Redirect;
use App;

class Items extends ComponentBase
{
    public $items;
    public $categoryPage;
    public $itemPage;
    public $noItemsMessage;

    public function componentDetails()
    {
        return [
            'name'        => 'OctoCase Item List',
            'description' => 'Displays a list of latest octocase items on the page.'
        ];
    }

    public function defineProperties()
    {
        return [
            'itemsPerPage' => [
                'title' => 'Items per page',
                'default' => '10',
                'type'=>'string',
                'validationPattern'=>'^[0-9]+$',
                'validationMessage'=>'Invalid format of the items per page value'
            ],
            'categoryPage' => [
                'title' => 'Category page',
                'description' => 'Name of the category page file for the "Published into" category links. This property is used by the default component partial.',
                'type'=>'dropdown',
                'default' => 'octocase/category'
            ],
            'itemPage' => [
                'title' => 'Item page',
                'description' => 'Name of the octocase item page file for the "Learn more" links. This property is used by the default component partial.',
                'type'=>'dropdown',
                'default' => 'octocase/item'
            ],
            'noItemsMessage' => [
                'title' => 'No items message',
                'description' => 'Message to display in the octocase item list in case if there are no items. This property is used by the default component partial.',
                'type'=>'string',
                'default' => 'No items found'
            ]
        ];
    }

    public function getCategoryPageOptions()
    {
        return CmsPropertyHelper::listPages();
    }

    public function getItemPageOptions()
    {
        return CmsPropertyHelper::listPages();
    }

    public function onRun()
    {
        $this->items = $this->page['items'] = $this->loadItems();

        $currentPage = $this->param('page');
        if ($currentPage > ($lastPage = $this->items->getLastPage()) && $currentPage > 1)
            return Redirect::to($this->controller->currentPageUrl(['page'=>$lastPage]));

        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->itemPage = $this->page['itemPage'] = $this->property('itemPage');
        $this->noItemsMessage = $this->page['noItemsMessage'] = $this->property('noItemsMessage');
    }

    protected function loadItems()
    {
        $currentPage = $this->param('page');
        App::make('paginator')->setCurrentPage($currentPage);

        return OctoCaseItem::isPublished()->orderBy('published_at', 'desc')->paginate($this->property('itemsPerPage'));
    }
}