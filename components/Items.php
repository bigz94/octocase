<?php namespace OctoDevel\OctoSlider\Components;

use \DB;
use Validator;
use Cms\Classes\ComponentBase;
use October\Rain\Support\ValidationException;
use OctoDevel\OctoSlider\Models\Item as SliderItem;

class Items extends ComponentBase
{
    public $slideItem;
    public $navigationCamera;
    public $loaderCamera;
    public $loaderColorCamera;
    public $loaderBgColorCamera;
    public $loaderOpacityCamera;
    public $loaderPaddingCamera;
    public $loaderStrokeCamera;
    public $barPositionCamera;
    public $barDirectionCamera;
    public $hoverCamera;
    public $thumbnailsCamera;
    public $playPauseCamera;
    public $paginationCamera;
    public $captionEffectCamera;

    public function componentDetails()
    {
        return [
            'name'        => 'OctoSlider Item',
            'description' => 'Display images from a selected slider item.'
        ];
    }

    public function defineProperties()
    {
        return [
            'idSlide' => [
                'title'        => 'Slider Item',
                'description'  => 'Choose the slider item that will display.',
                'type'         => 'dropdown',
                'default'      => '',
            ],
            'navigationCamera' => [
                'title'        => 'Display navigation',
                'description'  => 'If enabled the navigation button (prev, next and play/stop buttons) will be visible, if false they will be always hidden.',
                'type'         => 'dropdown',
                'default'      => 'false',
            ],
            'loaderCamera' => [
                'title'       => 'Display loader',
                'description' => 'Display a loader by slide.',
                'type'        => 'dropdown',
                'default'     => 'pie',
            ],
            'loaderColorCamera' => [
                'title'       => 'Loader color',
                'description' => 'Use a hexadecimal web color.',
                'type'        => 'string',
                'default'     => '#eeeeee',
            ],
            'loaderBgColorCamera' => [
                'title'       => 'Loader background color',
                'description' => 'Use a hexadecimal web color.',
                'type'        => 'string',
                'default'     => '#222222',
            ],
            'loaderOpacityCamera' => [
                'title'       => 'Loader opacity',
                'description' => 'Accpted values are: 0, .1, .2, .3, .4, .5, .6, .7, .8, .9, 1',
                'type'        => 'string',
                'default'     => '.8',
            ],
            'loaderPaddingCamera' => [
                'title'       => 'Loader padding',
                'description' => 'How many empty pixels you want to display between the loader and its background.',
                'type'        => 'string',
                'default'     => '2',
            ],
            'loaderStrokeCamera' => [
                'title'       => 'Loader stroke',
                'description' => 'The thickness both of the pie loader and of the bar loader. Remember: for the pie, the loader thickness must be less than a half of the pie diameter.',
                'type'        => 'string',
                'default'     => '7',
            ],
            'barPositionCamera' => [
                'title'       => 'Loader bar position',
                'description' => 'Choose the loader bar position.',
                'type'        => 'dropdown',
                'default'     => 'bottom',
            ],
            'barDirectionCamera' => [
                'title'       => 'Loader bar direction',
                'description' => 'Choose the loader bar direction if your "Display loader" choice was "Bar".',
                'type'        => 'dropdown',
                'default'     => 'leftToRight',
            ],
            'hoverCamera' => [
                'title'       => 'Pause on hover',
                'description' => 'Pause on state hover. Not available for mobile devices.',
                'type'        => 'dropdown',
                'default'     => 'true'
            ],
            'thumbnailsCamera' => [
                'title'       => 'Display thumbnails',
                'description' => 'Display thumbnails from the slider items image.',
                'type'        => 'dropdown',
                'default'     => 'true'
            ],
            'playPauseCamera' => [
                'title'       => 'Display play/pause buttons',
                'description' => 'Enter a category slug or URL parameter to filter the items by. Leave empty to show all items.',
                'type'        => 'dropdown',
                'default'     => 'false'
            ],
            'paginationCamera' => [
                'title'       => 'Display pagination',
                'description' => 'Display the slider pagination.',
                'type'        => 'dropdown',
                'default'     => 'true'
            ],
            'captionEffectCamera' => [
                'title'       => 'Image caption effect',
                'description' => 'Choose what effect the image captions will have.',
                'type'        => 'dropdown',
                'default'     => 'fadeFromBottom',
            ]
        ];
    }

    public function getIdSlideOptions()
    {
        $slides = SliderItem::all();

        $array_dropdown = ['0'=>'- select one - '];

        foreach ($slides as $slide)
        {
            $array_dropdown[$slide->id] = $slide->title;
        }

        return $array_dropdown;
    }

    public function getNavigationCameraOptions()
    {
        return ['false' => 'No', 'true' => 'Yes'];
    }

    public function getLoaderCameraOptions()
    {
        return ['pie' => 'Pie', 'bar' => 'Bar', 'none' => 'None'];
    }

    public function getHoverCameraOptions()
    {
        return ['false' => 'No', 'true' => 'Yes'];
    }

    public function getThumbnailsCameraOptions()
    {
        return ['false' => 'No', 'true' => 'Yes'];
    }

    public function getPlayPauseCameraOptions()
    {
        return ['false' => 'No', 'true' => 'Yes'];
    }

    public function getPaginationCameraOptions()
    {
        return ['false' => 'No', 'true' => 'Yes'];
    }

    public function getBarDirectionCameraOptions()
    {
        return ['leftToRight' => 'Left to right', 'rightToLeft' => 'Right to left', 'topToBottom' => 'Top to bottom', 'bottomToTop' => 'Bottom to top'];
    }

    public function getBarPositionCameraOptions()
    {
        return ['left' => 'Left', 'right' => 'Right', 'top' => 'Top', 'bottom' => 'Bottom'];
    }

    public function getCaptionEffectCameraOptions()
    {
        return ['moveFromLeft' => 'Move from left', 'moveFomRight' => 'Move from right', 'moveFromTop' => 'Move from top', 'moveFromBottom' => 'Move from bottom', 'fadeIn' => 'Fade in', 'fadeFromLeft' => 'Fade from left', 'fadeFromRight' => 'Fade from right', 'fadeFromTop' => 'Fade from top', 'fadeFromBottom' => 'Fade from bottom'];
    }

    public function onRun()
    {
        // Getting register
        $sliderItem = new SliderItem();
        $item = $sliderItem->where('id', '=', $this->propertyOrParam('idSlide'))->first();
        $this->slideItem = $this->page['slideItem'] = $item;

        // Setting parameters
        $this->navigationCamera = $this->page['navigationCamera'] = $this->propertyOrParam('navigationCamera');
        $this->loaderCamera = $this->page['loaderCamera'] = $this->propertyOrParam('loaderCamera');
        $this->loaderColorCamera = $this->page['loaderColorCamera'] = $this->propertyOrParam('loaderColorCamera');
        $this->loaderBgColorCamera = $this->page['loaderBgColorCamera'] = $this->propertyOrParam('loaderBgColorCamera');
        $this->loaderOpacityCamera = $this->page['loaderOpacityCamera'] = $this->propertyOrParam('loaderOpacityCamera');
        $this->loaderPaddingCamera = $this->page['loaderPaddingCamera'] = $this->propertyOrParam('loaderPaddingCamera');
        $this->loaderStrokeCamera = $this->page['loaderStrokeCamera'] = $this->propertyOrParam('loaderStrokeCamera');
        $this->barPositionCamera = $this->page['barPositionCamera'] = $this->propertyOrParam('barPositionCamera');
        $this->barDirectionCamera = $this->page['barDirectionCamera'] = $this->propertyOrParam('barDirectionCamera');
        $this->hoverCamera = $this->page['hoverCamera'] = $this->propertyOrParam('hoverCamera');
        $this->thumbnailsCamera = $this->page['thumbnailsCamera'] = $this->propertyOrParam('thumbnailsCamera');
        $this->playPauseCamera = $this->page['playPauseCamera'] = $this->propertyOrParam('playPauseCamera');
        $this->paginationCamera = $this->page['paginationCamera'] = $this->propertyOrParam('paginationCamera');
        $this->captionEffectCamera = $this->page['captionEffectCamera'] = $this->propertyOrParam('captionEffectCamera');

        // Add vendors
        $this->addCss('assets/vendor/pixedelic/camera/css/camera.css');
        $this->addJs('assets/vendor/pixedelic/camera/scripts/jquery.mobile.customized.min.js');
        $this->addJs('assets/vendor/pixedelic/camera/scripts/jquery.easing.1.3.js');
        $this->addJs('assets/vendor/pixedelic/camera/scripts/camera.min.js');
    }
}