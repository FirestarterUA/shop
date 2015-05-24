<?php namespace Firestarter\Shop\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Products Back-end Controller
 */
class Products extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

	
	public $requiredPermissions = ['firestarter.shop.access_products'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Firestarter.Shop', 'shop', 'products');
    }
}