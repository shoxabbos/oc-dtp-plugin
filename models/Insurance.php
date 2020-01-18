<?php namespace Itmaker\DtpApp\Models;

use Model;
use RainLab\User\Models\User;

/**
 * Model
 */
class Insurance extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    
    const SORT_ORDER = 'sort_order';

    /**
     * @var string The database table used by the model.
     */
    public $table = 'itmaker_dtpapp_insurances';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $hasOne = [
        'insurance' => User::class
    ];
}
