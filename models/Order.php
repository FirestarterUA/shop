<?php namespace Firestarter\Shop\Models;

use Model;
use October\Rain\Support\ValidationException;
use Firestarter\Shop\Models\Product as Product;
use Firestarter\Shop\Models\Coupon as Coupon;

/**
 * Order Model
 */
class Order extends Model
{   
	use \October\Rain\Database\Traits\Validation;	
	
	public $rules = [
        'email' => 'required|email',
        'product_id' => 'required|numeric'
    ];
	/**
     * @var string The database table used by the model.
     */
    public $table = 'firestarter_shop_orders';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
	
	public function getProductIdOptions()
	{
		return Product::lists('name', 'id');
	}
	
	public function getCouponIdOptions()
	{
		return Coupon::lists('value', 'id');
	}
	
	public function afterValidate()
	{
		/**
			А что если купон не соответствует своему товару
		*/
		if($this->coupon_id)
		{
			$coupon = Coupon::where('id', '=', $this->coupon_id)->where('product_id', '=', $this->product_id)->first();
			if($coupon==null)
			{	
				throw new ValidationException([
					'coupon_id' => 'Купон на скидку не соответствует своему товару',
				]);			
			}			
			
		}
			
	}

	
	public function afterSave()
	{
		/**
			Отмечаю купон как использованый
		*/
		Coupon::where('id', '=', $this->coupon_id)->where('product_id', '=', $this->product_id)->update(array('is_used' => $this->coupon_id));
	}
	
	public function afterDelete()
	{
		/**
			Удаляю купон если удалили заказ с этим купоном
		*/
		Coupon::where('id', '=', $this->coupon_id)->where('product_id', '=', $this->product_id)->delete();
	}
		

}