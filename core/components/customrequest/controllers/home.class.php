<?php

/**
 * Configs Controller Class
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
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('customrequest:default');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs()
    {
        $assetsUrl = $this->customrequest->getOption('assetsUrl');
        $jsUrl = $this->customrequest->getOption('jsUrl') . 'mgr/';
        $jsSourceUrl = $assetsUrl . '../../../source/js/mgr/';
        $cssUrl = $this->customrequest->getOption('cssUrl') . 'mgr/';
        $cssSourceUrl = $assetsUrl . '../../../source/css/mgr/';

        if ($this->customrequest->getOption('debug') && ($assetsUrl != MODX_ASSETS_URL . 'components/customrequest/')) {
            $this->addCss($cssSourceUrl . 'customrequest.css');
            $this->addJavascript($jsSourceUrl . 'customrequest.js');
            $this->addJavascript($jsSourceUrl . 'widgets/configs.grid.js');
            $this->addJavascript($jsSourceUrl . 'widgets/home.panel.js');
            $this->addLastJavascript($jsSourceUrl . 'sections/home.js');
        } else {
            $this->addCss($cssUrl . 'customrequest.min.css');
            $this->addJavascript($jsUrl . 'customrequest.min.js');
        }

        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            CustomRequest.config = ' . $this->modx->toJSON($this->customrequest->options) . ';
            MODx.load({ 
                xtype: \'customrequest-page-home\'
            });
        });
        </script>');
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
