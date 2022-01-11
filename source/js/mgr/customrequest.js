var customRequest = function (config) {
    config = config || {};
    customRequest.superclass.constructor.call(this, config);
};
Ext.extend(customRequest, Ext.Component, {
    initComponent: function () {
        this.stores = {};
        this.ajax = new Ext.data.Connection({
            disableCaching: true,
        });
    }, page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, util: {}, form: {}
});
Ext.reg('customrequest', customRequest);

CustomRequest = new customRequest();

MODx.config.help_url = 'https://jako.github.io/CustomRequest/usage/';
