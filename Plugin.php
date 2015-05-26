<?php namespace Firestarter\Shop;

use Backend;
use Controller;
use System\Classes\PluginBase;

/**
 * Shop Plugin Information File
 */
class Plugin extends PluginBase
{

    public $require = ['RainLab.Translate'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'firestarter.shop::lang.plugin.name',
            'description' => 'firestarter.shop::lang.plugin.description',
            'author'      => 'Firestarter',
            'icon'        => 'icon-shopping-cart'
        ];
    }
	
	 public function registerComponents()
    {
        return [
            'Firestarter\Shop\Components\Products'       => 'shopProducts',
			'Firestarter\Shop\Components\Product'       => 'shopProduct',
        ];
    }
    
    public function registerMailTemplates()
    {
        return [
            'firestarter.shop::mail.license' => 'Шаблон письма с лицензией на цифровый товар',
        ];
    }
	
	public function registerNavigation()
    {
        return [
            'shop' => [
                'label'       => 'firestarter.shop::lang.shop.menu_label',
                'url'         => Backend::url('firestarter/shop/products'),
                'icon'        => 'icon-shopping-cart',
                'permissions' => ['firestarter.shop.*'],
                'order'       => 500,
                'sideMenu' => [
                    'products' => [
                        'label'       => 'firestarter.shop::lang.shop.products',
                        'icon'        => 'icon-copy',
                        'url'         => Backend::url('firestarter/shop/products'),
                        'permissions' => ['firestarter.shop.access_products'],
                    ],
                    'categories' => [
                        'label'       => 'firestarter.shop::lang.shop.categories',
                        'icon'        => 'icon-copy',
                        'url'         => Backend::url('firestarter/shop/cats'),
                        'permissions' => ['firestarter.shop.access_categories'],
                    ],
                    'vendors' => [
                        'label'       => 'firestarter.shop::lang.shop.vendors',
                        'icon'        => 'icon-copy',
                        'url'         => Backend::url('firestarter/shop/vendors'),
                        'permissions' => ['firestarter.shop.access_categories'],
                    ],
                    'currencies' => [
                        'label'       => 'firestarter.shop::lang.shop.currencies',
                        'icon'        => 'icon-copy',
                        'url'         => Backend::url('firestarter/shop/currencies'),
                        'permissions' => ['firestarter.shop.access_coupons'],
                    ],
					'orders' => [
                        'label'       => 'firestarter.shop::lang.shop.orders',
                        'icon'        => 'icon-copy',
                        'url'         => Backend::url('firestarter/shop/orders'),
                        'permissions' => ['firestarter.shop.access_orders'],
                    ],
					'coupons' => [
                        'label'       => 'firestarter.shop::lang.shop.coupons',
                        'icon'        => 'icon-copy',
                        'url'         => Backend::url('firestarter/shop/coupons'),
                        'permissions' => ['firestarter.shop.access_coupons'],
                    ],
					
                    
                ]
            ]
        ];
    }
	
	public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'firestarter.shop::lang.shop.settings',
                'description' => 'firestarter.shop::lang.shop.settings_description',
                'category' => 'Shop',
                'icon' => 'icon-credit-card',
                'class' => 'Firestarter\Shop\Models\Settings',
                'order' => 500,
            ],
        ];
    }

}
