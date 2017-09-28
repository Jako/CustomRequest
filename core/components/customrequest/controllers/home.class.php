<?php
/**
 * Home controller class for SwitchTemplate
 *
 * @package customrequest
 * @subpackage controller
 */
class CustomrequestHomeManagerController extends modExtraManagerController
{
    /** @var CustomRequest $customrequest */
    public $customrequest;

    public function initialize()
    {
        $path = $this->modx->getOption('customrequest.core_path', null, $this->modx->getOption('core_path') . 'components/customrequest/');
        $this->customrequest = $this->modx->getService('customrequest', 'CustomRequest', $path . '/model/customrequest/');

        parent::initialize();
    }

    public function loadCustomCssJs()
    {
        $assetsUrl = $this->customrequest->getOption('assetsUrl');
        $jsUrl = $this->customrequest->getOption('jsUrl') . 'mgr/';
        $jsSourceUrl = $assetsUrl . '../../../source/js/mgr/';
        $cssUrl = $this->customrequest->getOption('cssUrl') . 'mgr/';
        $cssSourceUrl = $assetsUrl . '../../../source/css/mgr/';

        if ($this->customrequest->getOption('debug') && ($assetsUrl != MODX_ASSETS_URL . 'components/customrequest/')) {
            $this->addCss($cssSourceUrl . 'customrequest.css?v=v' . $this->customrequest->version);
            $this->addJavascript($jsSourceUrl . 'customrequest.js?v=v' . $this->customrequest->version);
            $this->addJavascript($jsSourceUrl . 'widgets/configs.grid.js?v=v' . $this->customrequest->version);
            $this->addJavascript($jsSourceUrl . 'widgets/home.panel.js?v=v' . $this->customrequest->version);
            $this->addLastJavascript($jsSourceUrl . 'sections/home.js?v=v' . $this->customrequest->version);
        } else {
            $this->addCss($cssUrl . 'customrequest.min.css?v=v' . $this->customrequest->version);
            $this->addJavascript($jsUrl . 'customrequest.min.js?v=v' . $this->customrequest->version);
        }
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            CustomRequest.config = ' . json_encode($this->customrequest->options, JSON_PRETTY_PRINT) . ';
            MODx.load({ xtype: "customrequest-page-home"});
        });
        </script>');
    }

    public function getLanguageTopics()
    {
        return array('customrequest:default');
    }

    public function process(array $scriptProperties = array())
    {
    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('customrequest');
    }

    public function getTemplateFile()
    {
        return $this->customrequest->getOption('templatesPath') . 'home.tpl';
    }
}
