<?php

namespace LeadpagesWP\Admin\Factories;

use TheLoop\Contracts\Factory;

class SettingsPage implements Factory
{

    public static function create($settingsPage)
    {
        $settingsPage = new $settingsPage();
        $settingsPage->registerPage();
    }
}