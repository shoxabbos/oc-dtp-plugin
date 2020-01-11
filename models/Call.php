<?php namespace Itmaker\DtpApp\Models;

use App;
use Model;
use Rainlab\User\Models\UserGroup;
use Itmaker\DtpApp\Resources\CallResource;
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

    protected $cats = [
        'coor_lat' => 'double',
        'coor_long' => 'double',
        'client_id' => 'integer',
        'id' => 'integer',
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
            'inprogress' => 'Выполняется',
            'arrived' => 'Сотрудник прибыл',
            'completed' => 'Завершён',
        ];
    }


    public function beforeSave()
    {
        if ($this->isDirty('employe_id') && $this->status == 'new' && $this->employe_id) {
            $this->status = 'inprogress';    
        }

        if ($this->isDirty()) {
            $data = new CallResource($this);
            $pusher = App::make('pusher');
            $pusher->trigger('call-status', "call-{$this->id}", $data);    
        }
    }

}
