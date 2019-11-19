<?php namespace Itmaker\DtpApp\Models;

use Model;

/**
 * Model
 * @method static where(string $string, bool $true)
 */
class Status extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'itmaker_dtpapp_statuses';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
