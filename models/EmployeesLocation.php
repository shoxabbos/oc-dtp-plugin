<?php namespace Itmaker\DtpApp\Models;

use Model;

/**
 * Model
 */
class EmployeesLocation extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'itmaker_dtpapp_employees_locations';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $guarded = [
        'id'
    ];

    public $belongsTo = [
        'user' => \Rainlab\User\Models\User::class
    ];
}
