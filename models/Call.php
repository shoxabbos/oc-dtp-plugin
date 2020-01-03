<?php namespace Itmaker\DtpApp\Models;

use App;
use Model;
use Rainlab\User\Models\UserGroup;
/**
 * Model call
 */
class Call extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['created_at', 'update_at', 'deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'itmaker_dtpapp_calls';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'client' => 'Rainlab\User\Models\User',
        'employe' => 'Rainlab\User\Models\User',
        'employe_group' => ['Rainlab\User\Models\UserGroup', 'key' => 'employe_group_code', 'otherKey' => 'code'],
    ];


    public $attachMany = [
        'images' => \System\Models\File::class,
    ];

    public $guarded = [
        'id'
    ];

    public $belongsToMany = [
        'services' => [Service::class, 'table' => 'itmaker_dtpapp_calls_services']
    ];

    /**
     * @return userGroup codes except clients
     */
    public function getTypeOptions()
    {
        return Service::TYPES;
    }


    public function getStatusOptions() {
        return [
            'new' => 'Новый',
            'active' => 'Активный',
            'approved' => 'Одобрен',
            'arrived' => 'Сотрудник прибыл',
            'completed' => 'Завершён',
        ];
    }
    
}
