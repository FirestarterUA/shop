<?php namespace Firestarter\Shop\Models;

use Model;

/**
 * Currency Model
 */
class Currency extends Model
{
	
	use \October\Rain\Database\Traits\Validation;	
	
	public $rules = [
        'name' => 'required',
		'value' => 'required|numeric|min:0,0001|max:10000.9999',
    ];
    /**
     * @var string The database table used by the model.
     */
    public $table = 'firestarter_shop_currencies';

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
	
	public function beforeSave()
	{
		if($this->is_default){
			Currency::where('is_default', '=', 1)->update(array('is_default' => 0));
			$this->value = 1.0000;
		}
	}

}