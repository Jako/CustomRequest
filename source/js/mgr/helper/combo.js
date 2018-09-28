CustomRequest.combo.Resource = function (config) {
    config = config || {};
    this.ident = config.ident || 'customrequest-mecitem' + Ext.id();
    Ext.applyIf(config, {
        url: CustomRequest.config.connectorUrl,
        baseParams: {
            action: 'mgr/resources/getlist',
            combo: true
        },
        pageSize: 10,
        fields: ['id', 'pagetitle'],
        displayField: 'pagetitle',
        valueField: 'id',
        lazyRender: true,
        editable: true,
        typeAhead: true,
        minChars: 1,
        forceSelection: true,
        triggerAction: 'all'
    });
    CustomRequest.combo.Resource.superclass.constructor.call(this, config);
};
Ext.extend(CustomRequest.combo.Resource, MODx.combo.ComboBox);
Ext.reg('customrequest-combo-resource', CustomRequest.combo.Resource);
