<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\FacebookPageWidgetByAmperage\Widgets;

use Piwik\Widget\Widget;
use Piwik\Widget\WidgetConfig;
use Piwik\View;
use Piwik\Common;
use Piwik\Site;
use Piwik\Url;
use Piwik\UrlHelper;
use Piwik\Plugin\SettingsProvider;
use Piwik\Settings\Measurable;
use Piwik\Settings\Measurable\MeasurableSettings;
use Piwik\Plugins\CorePluginsAdmin\SettingsMetadata;

/**
 * This class allows you to add your own widget to the Piwik platform. In case you want to remove widgets from another
 * plugin please have a look at the "configureWidgetsList()" method.
 * To configure a widget simply call the corresponding methods as described in the API-Reference:
 * http://developer.piwik.org/api-reference/Piwik/Plugin\Widget
 */
class GetFacebookPage extends Widget
{

    /**
     * @var SettingsProvider
     */
	private $settingsProvider;

    public function __construct(SettingsProvider $settingsProvider)
    {
        $this->settingsProvider = $settingsProvider;
    }

    public static function configure(WidgetConfig $config)
    {
        /**
         * Set the category the widget belongs to. You can reuse any existing widget category or define
         * your own category.
         */
        $config->setCategoryId('FacebookPageWidgetByAmperage_Social');

        /**
         * Set the subcategory the widget belongs to. If a subcategory is set, the widget will be shown in the UI.
         */
        // $config->setSubcategoryId('General_Overview');

        /**
         * Set the name of the widget belongs to.
         */
        $config->setName('FacebookPageWidgetByAmperage_FacebookPage');

        /**
         * Set the order of the widget. The lower the number, the earlier the widget will be listed within a category.
         */
        $config->setOrder(50);

        /**
         * Optionally set URL parameters that will be used when this widget is requested.
         * $config->setParameters(array('myparam' => 'myvalue'));
         */

        /**
         * Define whether a widget is enabled or not. For instance some widgets might not be available to every user or
         * might depend on a setting (such as Ecommerce) of a site. In such a case you can perform any checks and then
         * set `true` or `false`. If your widget is only available to users having super user access you can do the
         * following:
         *
         * $config->setIsEnabled(\Piwik\Piwik::hasUserSuperUserAccess());
         * or
         * if (!\Piwik\Piwik::hasUserSuperUserAccess())
         *     $config->disable();
         */
    }

    /**
     * This method renders the widget. It's on you how to generate the content of the widget.
     * As long as you return a string everything is fine. You can use for instance a "Piwik\View" to render a
     * twig template. In such a case don't forget to create a twig template (eg. myViewTemplate.twig) in the
     * "templates" directory of your plugin.
     *
     * @return string
     */
    public function render(){
        try {

			$output = '<div class="widget-body">';

			$facebook_page_url = '';
			$idSite = Common::getRequestVar('idSite');

			// Get the Facebook Page URL setting from Piwik based on the Measurable (Site/App) (allowing different sites/apps to have different Facebook Pages)
			$settings = $this->settingsProvider->getMeasurableSettings('FacebookPageWidgetByAmperage', $idSite);
			$facebook_page_url = $settings->facebookPageURLSetting->getValue();

			if($facebook_page_url == ''){
				$output.= '<p>You first need to configure the Facebook Page URL in your <a href="index.php?module=SitesManager&action=index#FacebookPageWidgetByAmperage">measurable (website/app) settings</a>.</p>';
			}else{ // Facebook Page URL has been provided

				$facebook_page_url = rtrim($facebook_page_url, '/') . '/'; // Ensure there's a trailing slash on the FB Page URL

				$output.= '<div id="fb-root"></div><script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = \'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=1468906493397233&autoLogAppEvents=1\';  fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script>';

				$output.= '<div class="fb-page" data-href="'.$facebook_page_url.'" data-tabs="timeline, events, messages" data-height="400" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="'.$facebook_page_url.'" class="fb-xfbml-parse-ignore"><a href="'.$facebook_page_url.'">MedQuarter</a></blockquote></div>';

				$output.= '<p><a href="'.$facebook_page_url.'insights/" target="_blank" class="more">View More Page Insights &amp; Stats</a></p>';

			}

			$output.= '</div>';
			return $output;

        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * @param \Exception $e
     * @return string
     */
    private function error($e)
    {
        return '<div class="pk-emptyDataTable">'
             . Piwik::translate('General_ErrorRequest', array('', ''))
             . ' - ' . $e->getMessage() . '</div>';
    }

}