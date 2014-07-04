<?php namespace OctoDevel\OctoSlider\Models;

use Model;
use October\Rain\Support\ValidationException;

class Item extends Model
{
    public $table = 'octodevel_octoslider_items';

    /*
     * Validation
     */
    public $rules = [
        'title' => 'required',
    ];

    public $attachMany = [
        'images' => ['System\Models\File', 'order' => 'sort_order'],
    ];

}