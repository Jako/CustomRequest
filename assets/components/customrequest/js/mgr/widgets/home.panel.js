CustomRequest.panel.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        cls: 'container',
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [{
            html: '<h2>' + _('customrequest') + '</h2>',
            cls: 'modx-page-header',
            border: false
        }, {
            defaults: {
                border: false,
                autoHeight: true
            },
            border: true,
            items: [{
                xtype: 'modx-tabs',
                deferredRender: false,
                forceLayout: true,
                defaults: {
                    layout: 'form',
                    autoHeight: true,
                    hideMode: 'offsets',
                    padding: 15
                },
                items: [{
                    xtype: 'customrequest-panel-configs'
                }]
            }]
        }]
    });
    CustomRequest.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(CustomRequest.panel.Home, MODx.Panel);
Ext.reg('customrequest-panel-home', CustomRequest.panel.Home);

CustomRequest.panel.Configs = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'customrequest-panel-configs',
        title: _('customrequest.configs'),
        items: [{
            html: '<p>' + _('customrequest.configs_desc') + '</p>',
            border: false
        }, {
            html: '<p>' + _('customrequest.configs_desc_extended') + '</p>',
            style: {
                'margin-top': '15px',
                'font-style': 'italic'
            },
            border: false
        }, {
            layout: 'form',
            id: 'customrequest-panel-configs-grid',
            cls: 'x-form-label-left',
            defaults: {
                border: false,
                autoHeight: true
            },
            border: true,
            items: [{
                xtype: 'customrequest-grid-configs',
                preventRender: true
            }]
        }]
    });
    CustomRequest.panel.Configs.superclass.constructor.call(this, config);
};
Ext.extend(CustomRequest.panel.Configs, MODx.Panel);
Ext.reg('customrequest-panel-configs', CustomRequest.panel.Configs);
