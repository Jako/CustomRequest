CustomRequest.grid.Configs = function (config) {
    config = config || {};
    this.ident = 'customrequest-configs-' + Ext.id();
    this.buttonColumnTpl = new Ext.XTemplate('<tpl for=".">'
        + '<tpl if="action_buttons !== null">'
        + '<ul class="action-buttons">'
        + '<tpl for="action_buttons">'
        + '<li><i class="icon {className} icon-{icon}" title="{text}"></i></li>'
        + '</tpl>'
        + '</ul>'
        + '</tpl>'
        + '</tpl>', {
        compiled: true
    });
    Ext.applyIf(config, {
        id: 'customrequest-grid-configs',
        url: CustomRequest.config.connectorUrl,
        baseParams: {
            action: 'mgr/configs/getlist'
        },
        fields: ['id', 'name', 'menuindex', 'alias', 'alias_gen', 'pagetitle', 'resourceid', 'context', 'urlparams', 'regex'],
        autoHeight: true,
        paging: true,
        remoteSort: false,
        enableDragDrop: true,
        ddGroup: 'customrequest-grid-dd',
        autoExpandColumn: 'alias',
        showActionsColumn: false,
        columns: [{
            header: _('customrequest.configs_name'),
            dataIndex: 'name',
            width: 100
        }, {
            header: _('customrequest.configs_alias'),
            dataIndex: 'alias_gen',
            renderer: CustomRequest.util.htmlRenderer,
            width: 150
        }, {
            header: _('customrequest.configs_resourceid'),
            dataIndex: 'pagetitle',
            width: 80
        }, {
            header: _('customrequest.configs_context'),
            dataIndex: 'context',
            width: 80
        }, {
            header: _('id'),
            dataIndex: 'id',
            hidden: true,
            width: 20
        }, {
            renderer: {
                fn: this.buttonColumnRenderer,
                scope: this
            },
            menuDisabled: true,
            width: 40
        }],
        tbar: [{
            text: _('customrequest.configs_create'),
            cls: 'primary-button',
            handler: this.createConfig
        }, '->', {
            xtype: 'textfield',
            id: this.ident + '-config-filter-search',
            emptyText: _('search') + '…',
            submitValue: false,
            listeners: {
                change: {
                    fn: this.search,
                    scope: this
                },
                render: {
                    fn: function (cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER,
                            fn: function () {
                                this.fireEvent('change', this);
                                this.blur();
                                return true;
                            },
                            scope: cmp
                        });
                    },
                    scope: this
                }
            }
        }, {
            xtype: 'button',
            id: this.ident + '-config-filter-clear',
            cls: 'x-form-filter-clear',
            text: _('filter_clear'),
            listeners: {
                click: {
                    fn: this.clearFilter,
                    scope: this
                }
            }
        }],
        listeners: {
            render: {
                fn: this.renderListener,
                scope: this
            }
        }
    });
    CustomRequest.grid.Configs.superclass.constructor.call(this, config);
};
Ext.extend(CustomRequest.grid.Configs, MODx.grid.Grid, {
    windows: {},
    getMenu: function () {
        var m = [];
        m.push({
            text: _('customrequest.configs_update'),
            handler: this.updateConfig
        });
        m.push('-');
        m.push({
            text: _('customrequest.configs_duplicate'),
            handler: this.duplicateConfig
        });
        m.push('-');
        m.push({
            text: _('customrequest.configs_remove'),
            handler: this.removeConfig
        });
        this.addContextMenuItem(m);
    },
    createConfig: function (btn, e) {
        this.createUpdateConfig(btn, e, false);
    },
    updateConfig: function (btn, e) {
        this.createUpdateConfig(btn, e, true);
    },
    createUpdateConfig: function (btn, e, isUpdate) {
        var r;
        if (isUpdate) {
            if (!this.menu.record || !this.menu.record.id) {
                return false;
            }
            r = this.menu.record;
        } else {
            r = {};
        }
        var createUpdateConfig = MODx.load({
            xtype: 'customrequest-window-config-create-update',
            isUpdate: isUpdate,
            title: (isUpdate) ? _('customrequest.configs_update') : _('customrequest.configs_create'),
            record: r,
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
        createUpdateConfig.fp.getForm().setValues(r);
        createUpdateConfig.show(e.target);
    },
    duplicateConfig: function (btn, e) {
        if (!this.menu.record) {
            return false;
        }
        var r = Ext.apply({}, this.menu.record);
        r.name = _('customrequest.duplicate') + ' ' + r.name;
        r.original_id = r.id;
        r.id = null;
        r.repeat_on = (r.repeat_on) ? r.repeat_on : [];
        var duplicateConfig = MODx.load({
            xtype: 'customrequest-window-config-create-update',
            isUpdate: false,
            title: _('customrequest.configs_duplicate'),
            record: r,
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                },
                beforeSubmit: function (values) {
                    duplicateConfig.beforeSubmit(values);
                }
            }
        });
        duplicateConfig.fp.getForm().setValues(r);
        duplicateConfig.show(e.target);
    },
    removeConfig: function () {
        if (!this.menu.record) {
            return false;
        }
        MODx.msg.confirm({
            title: _('customrequest.configs_remove'),
            text: _('customrequest.configs_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/configs/remove',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },
    search: function (tf) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    clearFilter: function () {
        var s = this.getStore();
        s.baseParams.query = '';
        Ext.getCmp(this.ident + '-config-filter-search').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    renderListener: function (grid) {
        new Ext.dd.DropTarget(grid.container, {
            ddGroup: 'customrequest-grid-dd',
            notifyDrop: function (dd, e, data) {
                var ds = grid.store;
                var sm = grid.getSelectionModel();
                var rows = sm.getSelections();
                var dragData = dd.getDragData(e);
                if (dragData) {
                    var cindex = dragData.rowIndex;
                    if (typeof (cindex) !== 'undefined') {
                        var target = ds.getAt(cindex);
                        var dragIds = [];
                        for (var i = 0; i < rows.length; i++) {
                            ds.remove(ds.getById(rows[i].id));
                            dragIds.push(rows[i].id);
                        }
                        ds.insert(cindex, data.selections);
                        sm.clearSelections();
                        grid.sortIndex(target.id, dragIds);
                        return true;
                    }
                }
                grid.getView().refresh();
            }
        })
    },
    sortIndex: function (targetId, movingIds) {
        MODx.Ajax.request({
            url: CustomRequest.config.connectorUrl,
            params: {
                action: 'mgr/configs/sortindex',
                targetId: targetId,
                movingIds: movingIds.join()
            },
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },
    buttonColumnRenderer: function () {
        var values = {
            action_buttons: [
                {
                    className: 'update',
                    icon: 'pencil-square-o',
                    text: _('customrequest.configs_update')
                }, {
                    className: 'duplicate',
                    icon: 'clone',
                    text: _('customrequest.configs_duplicate')
                }, {
                    className: 'remove',
                    icon: 'trash-o',
                    text: _('customrequest.configs_remove')
                }
            ]
        };
        return this.buttonColumnTpl.apply(values);
    },
    onClick: function (e) {
        var t = e.getTarget();
        var elm = t.className.split(' ')[0];
        if (elm === 'icon') {
            var act = t.className.split(' ')[1];
            var record = this.getSelectionModel().getSelected();
            this.menu.record = record.data;
            switch (act) {
                case 'remove':
                    this.removeConfig(record, e);
                    break;
                case 'duplicate':
                    this.duplicateConfig(record, e);
                    break;
                case 'update':
                    this.updateConfig(record, e);
                    break;
                default:
                    break;
            }
        }
    }
});
Ext.reg('customrequest-grid-configs', CustomRequest.grid.Configs);

CustomRequest.window.CreateUpdateConfig = function (config) {
    config = config || {};
    this.ident = 'customrequest-config-create-update-' + Ext.id();
    Ext.applyIf(config, {
        id: this.ident,
        url: CustomRequest.config.connectorUrl,
        action: (config.isUpdate) ? 'mgr/configs/update' : 'mgr/configs/create',
        autoHeight: true,
        closeAction: 'close',
        cls: 'modx-window customrequest-window modx' + CustomRequest.config.modxversion,
        fields: [{
            xtype: 'textfield',
            fieldLabel: _('customrequest.configs_name'),
            name: 'name',
            id: this.ident + '-name',
            anchor: '100%'
        }, {
            xtype: 'textfield',
            fieldLabel: _('customrequest.configs_alias'),
            description: _('customrequest.configs_alias_desc'),
            name: 'alias',
            id: this.ident + '-alias',
            anchor: '100%'
        }, {
            xtype: 'customrequest-combo-resource',
            fieldLabel: _('customrequest.configs_resourceid'),
            description: _('customrequest.configs_resourceid_desc'),
            name: 'resourceid',
            hiddenName: 'resourceid',
            id: this.ident + '-resourceid',
            anchor: '100%'
        }, {
            xtype: 'textarea',
            fieldLabel: _('customrequest.configs_urlparams'),
            description: _('customrequest.configs_urlparams_desc'),
            name: 'urlparams',
            id: this.ident + '-urlparams',
            anchor: '100%'
        }, {
            xtype: 'textarea',
            fieldLabel: _('customrequest.configs_regex'),
            description: _('customrequest.configs_regex_desc'),
            name: 'regex',
            id: this.ident + '-regex',
            anchor: '100%'
        }, {
            xtype: 'hidden',
            name: 'id',
            id: this.ident + '-id',
        }],
        listeners: {
            render: function (form) {
                Ext.create('Ext.tip.QuickTip', {
                    target: form.getEl()
                });
            }
        }
    });
    CustomRequest.window.CreateUpdateConfig.superclass.constructor.call(this, config);
};
Ext.extend(CustomRequest.window.CreateUpdateConfig, MODx.Window);
Ext.reg('customrequest-window-config-create-update', CustomRequest.window.CreateUpdateConfig);
