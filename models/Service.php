<?php namespace Itmaker\DtpApp\Models;

use Model;
use Rainlab\User\Models\UserGroup;


/**
 * Model
 */
class Service extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    const SORT_ORDER = 'sort_order';

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    const TYPES = [
        'crash' => 'Поломка',
        'accident' => 'ДТП',
        'tracker' => 'Эвакуатор',
    ];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'itmaker_dtpapp_services';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * Softly implement the TranslatableModel behavior.
     */
    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = ['name'];


    public $attachOne = [
        'icon' => \System\Models\File::class
    ];

    public function getTypeOptions() {
        return self::TYPES;
    }

}
