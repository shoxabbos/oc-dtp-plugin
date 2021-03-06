<?php namespace Itmaker\DtpApp\Models;

use Model;
use Rainlab\User\Models\UserGroup;
/**
 * Model
 */
class Tarif extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'itmaker_dtpapp_tarifs';

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
     * @var array Attributes that support translation, if available
     */
    public $translatable = ['name', 'description', 'amount'];

    /**
     * @var array Belongs To 
     */

    public $belongsTo = [
        'employe_group' => [ \Rainlab\User\Models\UserGroup::class, 'key' => 'employe_group_code', 'otherKey' => 'code']
    ];

    public $attachOne = [
        'image' => \System\Models\File::class,    
    ];

    public function getEmployeGroupCodeOptions()
    {
        return UserGroup::where('code', '!=', 'clients')->lists('name', 'code');
    }
}
