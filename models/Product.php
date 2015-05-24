<?php namespace Firestarter\Shop\Models;

use Model;

/**
 * Product Model
 */
class Product extends Model
{

	use \October\Rain\Database\Traits\Validation;	
	
	public $rules = [
        'name' => 'required',
        'slug'=>'required|unique:firestarter_shop_vendors',
		'price' => 'required'
    ];
    /**
     * @var string The database table used by the model.
     */
    public $table = 'firestarter_shop_products';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    protected $jsonable = ['options']; 

    protected $slugs = ['slug' => 'name'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'vendor' => ['Firestarter\Shop\Models\Vendor'],
    ];
    public $belongsToMany = [
        'categories' => ['Firestarter\Shop\Models\Category', 'table' => 'firestarter_shop_products_categories']
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = ['digital_product' => ['System\Models\File']];
    public $attachMany = ['featured_images' => ['System\Models\File']];

}