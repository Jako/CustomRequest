CustomRequest.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        buttons: [{
            text: _('help_ex'),
            handler: MODx.loadHelpPane
        }],
        formpanel: 'customrequest-panel-home',
        components: [{
            xtype: 'customrequest-panel-home'
        }]
    });
    CustomRequest.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(CustomRequest.page.Home, MODx.Component);
Ext.reg('customrequest-page-home', CustomRequest.page.Home);
