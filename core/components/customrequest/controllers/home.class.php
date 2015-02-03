<?php
/**
 * Configs controller class for CustomRequest CMP.
 *
 * @package customrequest
 * @subpackage controller
 */
require_once dirname(dirname(__FILE__)) . '/model/customrequest/customrequest.class.php';

class CustomrequestHomeManagerController extends modExtraManagerController
{
    /** @var CustomRequest $customrequest */
    public $customrequest;

    public function initialize()
    {
        $this->customrequest = new CustomRequest($this->modx);
    }

    public function loadCustomCssJs()
    {
        $this->addCss($this->customrequest->getOption('cssUrl') . 'mgr/customrequest.css');
        $this->addJavascript($this->customrequest->getOption('jsUrl') . 'mgr/customrequest.js');
        $this->addJavascript($this->customrequest->getOption('jsUrl') . 'mgr/widgets/configs.grid.js');
        $this->addJavascript($this->customrequest->getOption('jsUrl') . 'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->customrequest->getOption('jsUrl') . 'mgr/sections/home.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            CustomRequest.config = ' . $this->modx->toJSON($this->customrequest->options) . ';
            MODx.load({ xtype: \'customrequest-page-home\'});
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