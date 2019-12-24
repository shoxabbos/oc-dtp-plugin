<?php namespace Itmaker\DtpApp\Models;

use Model;
use Rainlab\User\Models\User;
/**
 * Model
 */
class Rate extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'itmaker_dtpapp_rates';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
    
    public $belongsTo = [
        'employe' => User::class,
        'user' => User::class
    ];
    
    public $guarded = [
        'id'    
    ];
}
