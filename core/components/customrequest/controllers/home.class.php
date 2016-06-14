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
        $this->addCss($this->customrequest->getOption('cssUrl') . 'mgr/customrequest.css');
        $this->addJavascript($this->customrequest->getOption('jsUrl') . 'mgr/customrequest.js');
        $this->addJavascript($this->customrequest->getOption('jsUrl') . 'mgr/widgets/configs.grid.js');
        $this->addJavascript($this->customrequest->getOption('jsUrl') . 'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->customrequest->getOption('jsUrl') . 'mgr/sections/home.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            CustomRequest.config = ' . $this->modx->toJSON($this->customrequest->config) . ';
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