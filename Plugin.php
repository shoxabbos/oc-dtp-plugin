<?php namespace Itmaker\DtpApp;

use App;
use Yaml;
use System\Classes\PluginBase;
use \Illuminate\Foundation\AliasLoader;
use Rainlab\User\Models\User as UserModel;
use Rainlab\User\Controllers\Users as UsersController;
use Rainlab\User\Models\UserGroup as UserGroupModel;
use Rainlab\User\Models\Settings as UserSettings;
use Itmaker\DtpApp\Models\Call as CallModel;
use Itmaker\DtpApp\Controllers\Calls as CallsController;
use \Itmaker\DtpApp\Models\Insurance;
            
class Plugin extends PluginBase
{
    
    public function boot()
    {
        $this->extendingModels();
        $this->extendingControllers();
        $this->extendingApp();
    }


    private function extendingModels()
    {
        UserModel::extend(function ($model) {
            $model->addFillable(['type', 'insurance_id']);
            
            $model->hasMany['calls'] = [ CallModel::class, 'key' => 'client_id'];
            $model->hasMany['employe_calls'] = [ CallModel::class, 'key' => 'employe_id'];
            $model->hasOne['insurance'] = Insurance::class;


            $model->addDynamicMethod('getInsuranceIdOptions', function() use ($model) {
                return Insurance::lists('name', 'id');
            });

        });
    }

    public function extendingControllers()
    {

        UsersController::extendListColumns(function($list, $model) {
            if (!$model instanceof UserModel) {
                return;
            }
            
            $list->addColumns([
                'type' => [
                    'label' => 'Client type',
                    'invisible' => true,
                ],
                'balance' => [
                    'label' => 'Balance',
                    'invisible' => true,
                ],
                'insurance_id' => [
                    'label' => 'Insurance',
                    'invisible' => true,
                ],
                'device_type' => [
                    'label' => 'Device type',
                    'invisible' => true,
                ],
                'device_id' => [
                    'label' => 'Device ID',
                    'invisible' => true,
                ]
            ]);

        });

        UsersController::extendFormFields(function ($form, $model, $context) {
            if (!$model instanceof UserModel) {
                return;
            }

            $fields = [
                'balance' => [
                    'type' => 'number',
                    'label' => 'Balance',
                    'span' => 'auto',
                    'tab' => 'DTP fields'
                ],
                'type' => [
                    'type' => 'dropdown',
                    'label' => 'Type',
                    'span' => 'auto',
                    'tab' => 'DTP fields',
                    'options' => [
                        'client' => 'Client',
                        'master' => 'Master',
                        'specialists' => 'Specialists',
                    ]
                ],
                'insurance_id' => [
                    'type' => 'dropdown',
                    'label' => 'Страховая компания',
                    'span' => 'auto',
                    'tab' => 'DTP fields',
                ],
            ];

            $form->addTabFields($fields);
        });
    }

    public function extendingApp()
    {
        App::register('Itmaker\DtpApp\Providers\PusherServiceProvider');
        App::register('Itmaker\DtpApp\Providers\PushNotificationProvider');

        $alias = AliasLoader::getInstance();
        $alias->alias('pusher', 'Itmaker\DtpApp\Providers\PusherServiceProvider');
        $alias->alias('fcm', 'Itmaker\DtpApp\Providers\PushNotificationProvider');
    }
}
