customRequest = function (config) {
    config = config || {};
    Ext.applyIf(config, {});
    customRequest.superclass.constructor.call(this, config);
    return this;
};
Ext.extend(customRequest, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, util: {}
});
Ext.reg('customrequest', customRequest);
CustomRequest = new customRequest();