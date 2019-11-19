<?php namespace Itmaker\DtpApp;

use Yaml;
use System\Classes\PluginBase;
use Rainlab\User\Models\User as UserModel;
use Rainlab\User\Models\UserGroup as UserGroupModel;
use Rainlab\User\Models\Settings as UserSettings;
use Itmaker\DtpApp\Models\Call as CallModel;
use Itmaker\DtpApp\Controllers\Calls as CallsController;
            
class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function register()
    {
        // this method registers console commands
        $this->registerConsoleCommand('itmaker.runwebsocket', 'Itmaker\DtpApp\Console\RunWebSocket');
    }

    public function boot()
    {
        $this->extendingModels();
        $this->extendingControllers();
    }


    private function extendingModels()
    {
        UserModel::extend(function ($model){
            $model->rules = [
                'email'    => 'between:6,255|email|unique:users',
                'avatar'   => 'nullable|image|max:4000',
                'username' => 'required|between:2,255|unique:users',
                'password' => 'required:create|between:' . UserSettings::MIN_PASSWORD_LENGTH_DEFAULT . ',255|confirmed',
                'password_confirmation' => 'required_with:password|between:' . UserSettings::MIN_PASSWORD_LENGTH_DEFAULT . ',255',
            ];
            $model->hasMany['calls'] = [ CallModel::class, 'key' => 'client_id'];
            $model->hasMany['employe_calls'] = [ CallModel::class, 'key' => 'employe_id'];

        });
    }

    public function extendingControllers()
    {

        CallsController::extendFormFields(function($form, $model, $context) {
            if (!$model instanceof CallModel) {
                return;
            }

            if (!$model->client) {
                $fields = Yaml::parseFile('./plugins/itmaker/dtpapp/models/call/user_not_fields.yaml');
                $form->addTabFields($fields);
            } else {
                $fields = Yaml::parseFile('./plugins/itmaker/dtpapp/models/call/user_fields.yaml');
                $form->addTabFields($fields);
            }

            if (!$model->employe) {
                $fields = Yaml::parseFile('./plugins/itmaker/dtpapp/models/call/employe_not_fields.yaml');
                $form->addTabFields($fields);
            } else {
                $fields = Yaml::parseFile('./plugins/itmaker/dtpapp/models/call/employe_fields.yaml');
                $form->addTabFields($fields);
            }

        });
    }
}
