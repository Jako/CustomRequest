CustomRequest.combo.Resource = function (config) {
    config = config || {};
    this.ident = config.ident || 'customrequest-mecitem' + Ext.id();
    Ext.applyIf(config, {
        pageSize: 10,
        fields: ['id', 'pagetitle'],
        displayField: 'pagetitle',
        valueField: 'id',
        lazyRender: true,
        editable: true,
        typeAhead: true,
        minChars: 1,
        forceSelection: true,
        url: CustomRequest.config.connectorUrl,
        baseParams: {
            action: 'mgr/resources/getList',
            combo: true
        }
    });
    CustomRequest.combo.Resource.superclass.constructor.call(this, config);
};
Ext.extend(CustomRequest.combo.Resource, MODx.combo.ComboBox);
Ext.reg('customrequest-combo-resource', CustomRequest.combo.Resource);

CustomRequest.grid.Configs = function (config) {

    /* action button renderer */
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

    config = config || {};
    Ext.applyIf(config, {
        id: 'customrequest-grid-configs',
        url: CustomRequest.config.connectorUrl,
        baseParams: {
            action: 'mgr/configs/getList'
        },
        save_action: 'mgr/configs/updateFromGrid',
        autosave: true,
        fields: ['id', 'name', 'menuindex', 'alias', 'pagetitle', 'resourceid', 'urlparams', 'regex'],
        autoHeight: true,
        paging: true,
        remoteSort: false,
        enableDragDrop: true,
        ddGroup: 'customrequest-grid-dd',
        autoExpandColumn: 'name',
        columns: [{
            header: _('id'),
            dataIndex: 'id',
            hidden: true,
            width: 20
        }, {
            header: _('customrequest.configs_name'),
            dataIndex: 'name',
            width: 100
        }, {
            header: _('customrequest.configs_alias'),
            dataIndex: 'alias',
            width: 80
        }, {
            header: _('customrequest.configs_resourceid'),
            dataIndex: 'pagetitle',
            width: 80
        }, {
            header: _('actions'),
            renderer: {
                fn: this.buttonColumnRenderer,
                scope: this
            },
            width: 40
        }],
        tbar: [{
            text: _('customrequest.configs_create'),
            cls: 'primary-button',
            handler: this.createConfig,
            scope: this
        }, '->', {
            xtype: 'textfield',
            id: 'customrequest-search-filter',
            emptyText: _('search') + '…',
            listeners: {
                'change': {
                    fn: this.search,
                    scope: this
                },
                'render': {
                    fn: function (cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER, fn: function () {
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
        }],
        listeners: {
            'render': {
                fn: this.renderListener,
                scope: this
            }
        }
    });
    CustomRequest.grid.Configs.superclass.constructor.call(this, config)
};
Ext.extend(CustomRequest.grid.Configs, MODx.grid.Grid, {
    windows: {}, getMenu: function () {
        var m = [],
            n = this.menu.record;
        m.push({
            text: _('customrequest.configs_update'), handler: this.updateConfig
        });
        m.push('-');
        m.push({
            text: _('customrequest.configs_remove'), handler: this.removeConfig
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
                'success': {
                    fn: function () {
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
        createUpdateConfig.fp.getForm().setValues(r);
        createUpdateConfig.show(e.target);
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
                'success': {
                    fn: function () {
                        this.refresh();
                    },
                    scope: this
                }
            }
        })
    },
    search: function (tf) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    renderListener: function (grid) {
        var ddrow = new Ext.dd.DropTarget(grid.container, {
            ddGroup: 'customrequest-grid-dd',
            notifyDrop: function (dd, e, data) {
                var ds = grid.store;
                var sm = grid.getSelectionModel();
                var rows = sm.getSelections();
                var dragData = dd.getDragData(e);
                if (dragData) {
                    var cindex = dragData.rowIndex;
                    if (typeof (cindex) !== "undefined") {
                        var target = ds.getAt(cindex);
                        var dragIds = [];
                        for (var i = 0; i < rows.length; i++) {
                            ds.remove(ds.getById(rows[i].id));
                            dragIds.push(rows[i].id);
                        }
                        ds.insert(cindex, data.selections);
                        sm.clearSelections();
                        grid.sortMenuIndex(target.id, dragIds);
                        return true;
                    }
                }
                grid.getView().refresh();
            }
        })
    },
    sortMenuIndex: function (targetId, movingIds) {
        MODx.Ajax.request({
            url: CustomRequest.config.connectorUrl,
            params: {
                action: 'mgr/configs/sortmenuindex',
                targetId: targetId,
                movingIds: movingIds.join()
            },
            listeners: {
                'success': {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },
    buttonColumnRenderer: function (value, metaData, record, rowIndex, colIndex, store) {
        var rec = record.data;
        var values = {
            action_buttons: [
                {
                    className: 'update',
                    icon: 'pencil-square-o',
                    text: _('delicart.order_update')
                },
                {
                    className: 'remove',
                    icon: 'trash-o',
                    text: _('delicart.order_remove')
                }
            ]
        };

        return this.buttonColumnTpl.apply(values);
    },
    onClick: function (e) {
        var t = e.getTarget();
        var elm = t.className.split(' ')[0];
        if (elm == 'icon') {
            var act = t.className.split(' ')[1];
            var record = this.getSelectionModel().getSelected();
            this.menu.record = record.data;
            switch (act) {
                case 'remove':
                    this.removeConfig(record, e);
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

Ext.QuickTips.init();

CustomRequest.window.CreateUpdateConfig = function (config) {
    config = config || {};
    this.ident = config.ident || 'customrequest-mecitem' + Ext.id();
    Ext.applyIf(config, {
        id: this.ident,
        url: CustomRequest.config.connectorUrl,
        action: (config.isUpdate) ? 'mgr/configs/update' : 'mgr/configs/create',
        autoHeight: true,
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
            xtype: 'textfield',
            name: 'id',
            id: this.ident + '-id',
            hidden: true
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

CustomRequest.combo.FieldconfigSource = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        pageSize: 10,
        fields: ['display', 'fieldname'],
        displayField: 'display',
        valueField: 'fieldname',
        lazyRender: true,
        editable: true,
        typeAhead: true,
        minChars: 1,
        forceSelection: true,
        url: CustomRequest.config.connectorUrl,
        baseParams: {
            action: 'mgr/csvconfigs/getList',
            recordId: config.recordId,
            combo: true
        }
    });
    CustomRequest.combo.FieldconfigSource.superclass.constructor.call(this, config);
};
Ext.extend(CustomRequest.combo.FieldconfigSource, MODx.combo.ComboBox);
Ext.reg('customrequest-combo-fieldconfig-source', CustomRequest.combo.FieldconfigSource);

