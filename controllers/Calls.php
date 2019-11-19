<?php namespace Itmaker\DtpApp\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Calls extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'manage_calls' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Itmaker.DtpApp', 'calls', 'calls');
    }
}
