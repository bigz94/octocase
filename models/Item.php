<?php namespace OctoDevel\OctoCase\Models;

use App;
use Str;
use Model;
use October\Rain\Support\Markdown;
use October\Rain\Support\ValidationException;

class Item extends Model
{
    public $table = 'octodevel_octocase_items';

    /*
     * Validation
     */
    public $rules = [
        'title' => 'required',
        'slug' => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:octodevel_octocase_items'],
        'content' => 'required',
        'excerpt' => ''
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['published_at'];

    public $belongsToMany = [
        'categories' => ['OctoDevel\OctoCase\Models\Category', 'table' => 'octodevel_octocase_items_categories', 'order' => 'name']
    ];

    public $attachMany = [
        'images' => ['System\Models\File', 'order' => 'sort_order'],
    ];

    /**
     * Lists items for the front end
     * @param  array $options Display options
     * @return self
     */
    public function listFrontEnd($options)
    {
        /*
         * Default options
         */
        extract(array_merge([
            'page' => 1,
            'perPage' => 30,
            'sort' => 'created_at',
            'categories' => null,
            'search' => '',
            'published' => true
        ], $options));

        $allowedSortingOptions = ['created_at', 'updated_at', 'published_at'];
        $searchableFields = ['title', 'slug', 'excerpt', 'content'];

        App::make('paginator')->setCurrentPage($page);
        $obj = $this->newQuery();

        if ($published)
            $obj->isPublished();

        /*
         * Sorting
         */
        if (!is_array($sort)) $sort = [$sort];
        foreach ($sort as $_sort) {

            $parts = explode(' ', $_sort);
            if (count($parts) < 2) array_push($parts, 'desc');
            list($sortField, $sortDirection) = $parts;

            if (in_array($sortField, $allowedSortingOptions))
                $obj->orderBy($_sort, 'desc');
        }

        /*
         * Search
         */
        $search = trim($search);
        if (strlen($search)) {
            $obj->searchWhere($search, $searchableFields);
        }

        /*
         * Categories
         */
        if ($categories !== null) {
            if (!is_array($categories)) $categories = [$categories];
            $obj->whereHas('categories', function($q) use ($categories) {
                $q->whereIn('id', $categories);
            });
        }

        return $obj->paginate($perPage);
    }

    public function afterValidate()
    {
        if ($this->published && !$this->published_at)
            throw new ValidationException([
               'published_at' => 'Please specify the published date'
            ]);
    }

    public function scopeIsPublished($query)
    {
        return $query
            ->whereNotNull('published')
            ->where('published', '=', 1)
        ;
    }

    public function beforeCreate()
    {
        // Update content text
        $this->content_text = strip_tags($this->content);

        // Set current date
        if($this->published_at)
        {
            $timestamp = strtotime($this->published_at);
            $this->published_at = date('Y-m-d', $timestamp) . ' ' . date('H:i:s');
        }
    }

    public function beforeUpdate()
    {
        // Update content text
        $this->content_text = strip_tags($this->content);

        // Original date
        $original = $this->original;
        $org_timestamp = strtotime($original['published_at']);
        $org_published_at = date('Y-m-d', $org_timestamp);

        // Set current date
        $timestamp = strtotime($this->published_at);
        $published_at = date('Y-m-d', $timestamp);

        if($published_at != $org_published_at)
        {
            $this->published_at = $published_at . ' ' . date('H:i:s');
        }
        else
        {
            $this->published_at = date('Y-m-d H:i:s', $org_timestamp);
        }
    }
}