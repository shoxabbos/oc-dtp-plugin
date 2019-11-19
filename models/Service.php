<?php namespace Itmaker\DtpApp\Models;

use Model;
use Rainlab\User\Models\UserGroup;


/**
 * Model
 */
class Service extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\NestedTree;
    //use \October\Rain\Database\Traits\Sortable;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


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

    public $belongsTo = [
        'employe_group' => [
             \Rainlab\User\Models\UserGroup::class,
             'key' => 'employe_group_code',
             'otherKey' => 'code'
        ],
        'parent'    => [Servise::class, 'key' => 'parent_id'],
    ];


    public $hasMany = [
        'childrens'    => [Service::class, 'key' => 'parent_id'],
    ];

    public function getEmployeGroupCodeOptions()
    {
        return UserGroup::where('code', '!=', 'clients')->lists('name', 'code');
    }

    public function getTabOptions()
    {
        return UserGroup::where('code', '!=', 'clients')->lists('name', 'code');
    }
}
