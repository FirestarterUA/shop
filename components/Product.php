<?php namespace Firestarter\Shop\Components;

use Input;
use Validator;
use Cms\Classes\ComponentBase;
use Firestarter\Shop\Models\Product as shopProduct;
use Firestarter\Shop\Models\Coupon as Coupon;
use Firestarter\Shop\Models\Currency as Currency;
use Firestarter\Shop\Models\Settings as Settings;

class Product extends ComponentBase
{
	public $product;
	
	public $currencies;
    
    public $settings;
    
	public function componentDetails()
    {
        return [
            'name'        => 'Product Component',
            'description' => 'One product page.'
        ];
    }

    public function defineProperties()
    {
        return [
            'idParam' => [
                'title' => 'Slug',
                'default' => ':slug',
                'type' => 'string',
            ],
        ];
    }
	
	public function onRun()
    {
        /**
            Добавляю фотораму стили и код в страницу товара
        */
        $this->addCss('http://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.3/fotorama.css');
        $this->addJs('http://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.3/fotorama.min.js');
        /**
            
        */
        $this->settings = Settings::instance();
        $this->currencies = $this->page['currencies'] = Currency::get()->toArray();  
		$this->product = $this->page['product'] = $this->loadProduct();
        /**
            Переопределяю мета теги
        */
        $this->page->meta_description = mb_substr(strip_tags($this->product->description),0,255,'UTF-8');
        $this->page->title = $this->product->name.' '.$this->product->model;
    }
    
    public function onChangeCarrency()
    {
   
        $data = Input::all();
        
        $rules = [
            'user_name' => 'required', 
            'user_email' => 'required|email',
            'user_coupon'=>'coupon'
            ];
        $messages = [
            'required' => ' Поле :attribute обязательное для заполнения',
            'email' => 'Почта должна соответствовать adress@site.domain',
            'coupon'=>'Такого купона нет или уже использвется'
            ];
        
        Validator::extend('coupon', function($attribute, $value, $parameters)
        {
            $product_id = $this->propertyOrParam('idParam');
            /**
                $value, позможно, нужно защитить, хз))
            */
            return $this->loadCouponByValue($value);
        });
        
        $validation = Validator::make($data, $rules, $messages);

        if ($validation->fails())
                
        {
            /**
                Валидация не пройдена
            */
            $this->page['errors'] = $validation->messages()->all();            
        }else{
            /**
                Валидация пройдена
            */
            $this->page['user'] = $data;
            $this->page['product'] = $this->loadProduct();
            $this->page['coupon'] = $this->loadCouponByValue($data['user_coupon']);
            $this->page['settings'] = Settings::instance();
            $this->page['currencies'] = Currency::get()->keyBy('id')->toArray();
        }
    }
    
    protected function loadCouponByValue($value)
    {
         $product_id = $this->param('id');
         /**
                $value, позможно, нужно защитить, хз))
         */
         return Coupon::where('product_id', '=', $product_id)->where('value', '=', $value)->first();
    }
	
	 protected function loadProduct()
    {
        $product_id = $this->param('id');
		$product = shopProduct::find($product_id);
		if(!$product)
			return $this->controller->run('404');
        return $product;
    }

}