<?php namespace Firestarter\Shop\Components;

use Cms\Classes\ComponentBase;
use Firestarter\Shop\Models\Product as Product;
use Firestarter\Shop\Models\Currency as Currency;

class Products extends ComponentBase
{
    public $products;
	
	public $currencies;
	
    public function componentDetails()
    {
        return [
            'name'        => 'Products Component',
            'description' => 'Echo list of products to page'
        ];
    }

    public function defineProperties()
    {
        return [];
    }
	
	 public function onRun()
    {
		$this->currencies = $this->page['currencies'] = Currency::get()->toArray();  
		$this->products = $this->page['products'] = Product::get()->toArray();       
    }
}