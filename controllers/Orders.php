<?php namespace Firestarter\Shop\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Orders Back-end Controller
 */
class Orders extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Firestarter.Shop', 'shop', 'orders');
    }
}