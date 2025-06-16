<?php
/**
 * Created by PhpStorm.
 * User: baghraja
 * Date: 9/18/19
 * Time: 9:31 PM
 */

namespace Support\Services\Settings;


use Support\Entities\UserSetting;
use Support\Exceptions\ApiException;

class UserSettingsResolver
{
    protected $settings;
    protected $resolved = [];

    public function __construct()
    {
        $this->INIT();
    }

    public function INIT()
    {
        $this->settings = UserSetting::sameOrg()->get();
        if ($this->settings->count() == 0) {
            $this->initFromConfig();
        }
        //pd($this->settings->toArray());
    }

    public function initFromConfig()
    {
        $config = config('user_settings.user_default_settings');
        //dd($config);
        foreach ($config as $key => $value) {
            UserSetting::create([
                'key' => $key,
                'options' => $value
            ]);
        }
        $this->settings = UserSetting::sameOrg()->get();
    }

    public function all()
    {
        // $this->get('membership.email_notification');
        return $this->settings->toArray();
    }


    public function get($name)
    {
        if (!isset($this->resolved[$name])) {
            $parts = explode(".", $name);
            $row = $this->settings->where('key', $parts[0])->first();
            if (!$row) {
                return false;
            }

            if ($row && count($parts) == 1) {
                return $row->options;
            }
            //dd(@collect($row->options));
            $this->resolved[$name] = @collect($row->options)->where('key', $parts[1])->first()['value'];

        }

        return $this->resolved[$name];
    }


    public function updateByKey($key, $options)
    {
        $setting = UserSetting::where('key', '=', $key)->first();
        if (!$setting) {
            throw new ApiException('INVALID ACCESS');
        }

        $setting->options = $options;
        $setting->save();

        return $setting;
    }
}