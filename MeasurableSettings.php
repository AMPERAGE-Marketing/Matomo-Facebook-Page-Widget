<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\FacebookPageWidgetByAmperage;

use Piwik\Settings\Setting;
use Piwik\Settings\FieldConfig;

/**
 * Defines Settings for FacebookPageWidgetByAmperage.
 *
 * Usage like this:
 * // require Piwik\Plugin\SettingsProvider via Dependency Injection eg in constructor of your class
 * $settings = $settingsProvider->getMeasurableSettings('FacebookPageWidgetByAmperage', $idSite);
 * $settings->appId->getValue();
 * $settings->contactEmails->getValue();
 */
class MeasurableSettings extends \Piwik\Settings\Measurable\MeasurableSettings
{

    /** @var Setting */
    public $facebookPageURLSetting;

    protected function init()
    {
        $this->facebookPageURLSetting = $this->makeFacebookPageURLSetting();
    }

    private function makeFacebookPageURLSetting()
    {
        return $this->makeSetting('facebookPageURL', $default = '', FieldConfig::TYPE_STRING, function (FieldConfig $field) {
            $field->title = 'Facebook Page URL';
            $field->uiControl = FieldConfig::UI_CONTROL_TEXT;
            $field->uiControlAttributes = array('size' => 3);
            $field->description = 'The URL for your Facebook Page (ex. "https://www.facebook.com/AmperageMarketing/")';
        });
    }

}
