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
            border: false,
            cls: 'modx-page-header'
        }, {
            defaults: {
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
                    hideMode: 'offsets'
                },
                items: [{
                    xtype: 'customrequest-panel-configs'
                }]
            }]
        }, {
            cls: "treehillstudio_about",
            html: '<img width="133" height="40" src="' + CustomRequest.config.assetsUrl + 'img/treehill-studio-small.png"' + ' srcset="' + CustomRequest.config.assetsUrl + 'img/treehill-studio-small@2x.png 2x" alt="Treehill Studio">',
            listeners: {
                afterrender: function (component) {
                    component.getEl().select('img').on('click', function () {
                        var msg = '<span style="display: inline-block; text-align: center"><img src="' + CustomRequest.config.assetsUrl + 'img/treehill-studio.png" srcset="' + CustomRequest.config.assetsUrl + 'img/treehill-studio@2x.png 2x" alt"Treehill Studio"><br>' +
                            '© 2013-2017 by <a href="https://treehillstudio.com" target="_blank">treehillstudio.com</a></span>';
                        Ext.Msg.show({
                            title: _('customrequest') + ' ' + CustomRequest.config.version,
                            msg: msg,
                            buttons: Ext.Msg.OK,
                            cls: 'treehillstudio_window',
                            width: 330
                        });
                    });
                }
            }
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
            border: false,
            bodyCssClass: 'panel-desc'
        }, {
            cls: 'main-wrapper',
            items: [{
                html: '<p>' + _('customrequest.configs_desc_extended') + '</p>',
                style: {
                    'margin-bottom': '10px',
                    'font-style': 'italic'
                },
                border: false
            }, {
                layout: 'form',
                id: 'customrequest-panel-configs-grid',
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
        }]
    });
    CustomRequest.panel.Configs.superclass.constructor.call(this, config);
}
;
Ext.extend(CustomRequest.panel.Configs, MODx.Panel);
Ext.reg('customrequest-panel-configs', CustomRequest.panel.Configs);
