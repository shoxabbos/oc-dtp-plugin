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
            $model->addFillable(['type']);

            $model->rules = [
                'email'    => 'between:6,255|email|unique:users',
                'avatar'   => 'nullable|image|max:4000',
                'username' => 'required|between:2,255|unique:users',
                'password' => 'required:create|between:' . UserSettings::MIN_PASSWORD_LENGTH_DEFAULT . ',255|confirmed',
                'password_confirmation' => 'required_with:password|between:' . UserSettings::MIN_PASSWORD_LENGTH_DEFAULT . ',255',
            ];
            
            $model->hasMany['calls'] = [ CallModel::class, 'key' => 'client_id'];
            $model->hasMany['employe_calls'] = [ CallModel::class, 'key' => 'employe_id'];
            $model->hasMany['locations'] = \Itmaker\DtpApp\Models\EmployeesLocation::class;

        });
    }

    public function extendingControllers()
    {

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
                ]
            ];

            $form->addTabFields($fields);
        });
    }

    public function extendingApp()
    {
        App::register('Itmaker\DtpApp\Providers\PusherServiceProvider');
        $alias = AliasLoader::getInstance();
        $alias->alias('pusher', 'Itmaker\DtpApp\Providers\PusherServiceProvider');
    }
}
