CustomRequest.panel.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        cls: 'container home-panel' + ((CustomRequest.config.debug) ? ' debug' : '') + ' modx' + CustomRequest.config.modxversion,
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [{
            html: '<h2>' + _('customrequest') + '</h2>' + ((CustomRequest.config.debug) ? '<div class="ribbon top-right"><span>' + _('customrequest.debug_mode') + '</span></div>' : ''),
            border: false,
            cls: 'modx-page-header'
        }, {
            defaults: {
                autoHeight: true
            },
            border: true,
            cls: 'customrequest-panel',
            items: [{
                xtype: 'customrequest-panel-overview'
            }]
        }, {
            cls: 'treehillstudio_about',
            html: '<img width="146" height="40" src="' + CustomRequest.config.assetsUrl + 'img/mgr/treehill-studio-small.png"' + ' srcset="' + CustomRequest.config.assetsUrl + 'img/mgr/treehill-studio-small@2x.png 2x" alt="Treehill Studio">',
            listeners: {
                afterrender: function () {
                    this.getEl().select('img').on('click', function () {
                        var msg = '<span style="display: inline-block; text-align: center"><img src="' + CustomRequest.config.assetsUrl + 'img/mgr/treehill-studio.png" srcset="' + CustomRequest.config.assetsUrl + 'img/mgr/treehill-studio@2x.png 2x" alt="Treehill Studio"><br>' +
                            '&copy; 2013-2022 by <a href="https://treehillstudio.com" target="_blank">treehillstudio.com</a></span>';
                        Ext.Msg.show({
                            title: _('customrequest') + ' ' + CustomRequest.config.version,
                            msg: msg,
                            buttons: Ext.Msg.OK,
                            cls: 'treehillstudio_window',
                            width: 358
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

CustomRequest.panel.HomeTab = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'customrequest-panel-' + config.tabtype,
        title: config.title,
        items: [{
            html: '<p>' + config.description + '</p>',
            border: false,
            cls: 'panel-desc'
        }, {
            layout: 'form',
            cls: 'x-form-label-left main-wrapper',
            defaults: {
                autoHeight: true
            },
            border: true,
            items: [{
                id: 'customrequest-panel-' + config.tabtype + '-grid',
                xtype: 'customrequest-grid-' + config.tabtype,
                preventRender: true
            }]
        }]
    });
    CustomRequest.panel.HomeTab.superclass.constructor.call(this, config);
};
Ext.extend(CustomRequest.panel.HomeTab, MODx.Panel);
Ext.reg('customrequest-panel-hometab', CustomRequest.panel.HomeTab);

CustomRequest.panel.Overview = function (config) {
    config = config || {};
    this.ident = 'customrequest-panel-overview-' + Ext.id();
    this.panelOverviewTabs = [{
        xtype: 'customrequest-panel-hometab',
        title: _('customrequest.configs'),
        description: _('customrequest.configs_desc') + '<div style="margin-top: 15px;font-style: italic">' + _('customrequest.configs_desc_extended') + '</div>',
        tabtype: 'configs'
    }];
    if (CustomRequest.config.is_admin) {
        this.panelOverviewTabs.push({
            xtype: 'customrequest-panel-settings'
        })
    }
    Ext.applyIf(config, {
        id: this.ident,
        items: [{
            xtype: 'modx-tabs',
            border: true,
            stateful: true,
            stateId: 'customrequest-panel-overview',
            stateEvents: ['tabchange'],
            getState: function () {
                return {
                    activeTab: this.items.indexOf(this.getActiveTab())
                };
            },
            autoScroll: true,
            deferredRender: true,
            forceLayout: false,
            defaults: {
                layout: 'form',
                autoHeight: true,
                hideMode: 'offsets'
            },
            items: this.panelOverviewTabs,
            listeners: {
                tabchange: function (o, t) {
                    if (t.xtype === 'customrequest-panel-settings') {
                        Ext.getCmp('customrequest-grid-system-settings').getStore().reload();
                    } else if (t.xtype === 'customrequest-panel-hometab') {
                        if (Ext.getCmp('customrequest-panel-' + t.tabtype + '-grid')) {
                            Ext.getCmp('customrequest-panel-' + t.tabtype + '-grid').getStore().reload();
                        }
                    }
                }
            }
        }]
    });
    CustomRequest.panel.Overview.superclass.constructor.call(this, config);
};
Ext.extend(CustomRequest.panel.Overview, MODx.Panel);
Ext.reg('customrequest-panel-overview', CustomRequest.panel.Overview);
