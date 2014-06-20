<?php namespace OctoDevel\OctoCase\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use OctoDevel\OctoCase\Models\Item;

class Items extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('OctoDevel.OctoCase', 'octocase', 'items');
        $this->addCss('/plugins/octodevel/octocase/assets/css/octodevel.octocase-preview.css');
        $this->addCss('/plugins/octodevel/octocase/assets/css/octodevel.octocase-preview-theme-default.css');

        $this->addCss('/plugins/octodevel/octocase/assets/vendor/prettify/prettify.css');
        $this->addCss('/plugins/octodevel/octocase/assets/vendor/prettify/theme-desert.css');

        $this->addJs('/plugins/octodevel/octocase/assets/js/item-form.js');
        $this->addJs('/plugins/octodevel/octocase/assets/vendor/prettify/prettify.js');
    }

    public function onRefreshPreview()
    {
        $data = post('Item');

        $previewHtml = Item::formatHtml($data['content'], true);

        return [
            'preview' => $previewHtml
        ];
    }
}