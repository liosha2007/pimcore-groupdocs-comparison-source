pimcore.registerNS("pimcore.plugin.GroupDocsComparison");

pimcore.plugin.GroupDocsComparison = Class.create(pimcore.plugin.admin, {
	getClassName : function() {
		return "pimcore.plugin.GroupDocsComparison";
	},

	initialize : function() {
		pimcore.plugin.broker.registerPlugin(this);
	},

	pimcoreReady : function(params, broker) {
		// add a sub-menu item under "Extras" in the main menu
		var toolbar = Ext.getCmp("pimcore_panel_toolbar");

		var action = new Ext.Action({
					id : "groupdocs_comparison_plugin_menu_item",
					text : "Configure GroupDocs Comparison",
					iconCls : "groupdocs_comparison_plugin_menu_icon",
					handler : this.showTab
				});

		toolbar.items.items[1].menu.add(action);
	},

	showTab : function() {
		Ext.Ajax.request({
					url : '/plugin/GroupDocsComparison/group-docs-comparison-admin/loaddata',
					success : function(response, options) {
						var objAjax = Ext.decode(response.responseText);
						groupDocsComparison.dataLoaded(objAjax);
					},
					failure : function(response, options) {
						Ext.MessageBox.show({
									title : 'GroupDocs Plugin Error',
									msg : 'GroupDocs Plugin Error - can\'t load data!',
									buttons : Ext.MessageBox.OK,
									animateTarget : 'mb9',
									icon : Ext.MessageBox.ERROR
								});
					}
				});

	},
	dataLoaded : function(objAjax) {
		groupDocsComparison.panel = new Ext.Panel({
					id : "groupdocs_comparison_plugin_tab_panel",
					title : "Configure GroupDocs Comparison",
					iconCls : "groupdocs_comparison_plugin_tab_icon",
					border : false,
					layout : {
						type: 'table',
						columns: 2
					},
					closable : true,
					items : [
						{
							xtype : 'label',
							text : 'Client ID: ',
							style: 'margin: 8px 3px 3px 8px;'
						},
						{
                            xtype: 'textfield',
                            id : 'cid',
                            value: objAjax.configs.cid,
                            width: 250,
                            allowBlank: false,
                            style: 'margin: 8px 3px 3px 3px;'
                        },
						{
							xtype : 'label',
							text : 'Private Key: ',
							style: 'margin: 8px 3px 3px 8px;'
						},
						{
                            xtype: 'textfield',
                            id : 'pkey',
                            value: objAjax.configs.pkey,
                            width: 250,
                            allowBlank: false,
                            style: 'margin: 8px 3px 3px 3px;'
                        },
						{
							xtype : 'label',
							text : 'First file ID: ',
							style: 'margin: 8px 3px 3px 8px;'
						},
						{
                            xtype: 'textfield',
                            id : 'firstfileid',
                            value: objAjax.configs.firstfileid,
                            width: 250,
                            allowBlank: false,
                            style: 'margin: 8px 3px 3px 3px;'
                        },
						{
							xtype : 'label',
							text : 'Second file ID: ',
							style: 'margin: 8px 3px 3px 8px;'
						},
						{
                            xtype: 'textfield',
                            id : 'secondfileid',
                            value: objAjax.configs.secondfileid,
                            width: 250,
                            allowBlank: false,
                            style: 'margin: 8px 3px 3px 3px;'
                        },
                        {
                            xtype : 'label',
                            text : 'Frame border width: ',
                            style: 'margin: 3px 3px 3px 8px;'
                        },
                        {
                            id: 'frameborder',
                            xtype: 'numberfield',
                            value: objAjax.configs.frameborder,
                            width: 250,
                            allowBlank: false,
                            style: 'margin: 3px;'
                        },
                        {
                            xtype : 'label',
                            text : 'Frame width: ',
                            style: 'margin: 3px 3px 3px 8px;'
                        },
                        {
                            id: 'width',
                            xtype: 'numberfield',
                            value: objAjax.configs.width,
                            width: 250,
                            allowBlank: false,
                            style: 'margin: 3px;'
                        },
                        {
                            xtype : 'label',
                            text : 'Frame height: ',
                            style: 'margin: 3px 3px 3px 8px;'
                        },
                        {
                            id: 'height',
                            xtype: 'numberfield',
                            value: objAjax.configs.height,
                            width: 250,
                            allowBlank: false,
                            style: 'margin: 3px;'
                        },
                        {
                        	xtype: 'button',
                        	text: 'Save',
                        	colspan: 2,
                            width: 150,
                            style: 'margin: 3px 3px 3px 8px;',
                            handler: groupDocsComparison.saveClick
                        }
					]
				});

		var tabPanel = Ext.getCmp("pimcore_panel_tabs");
		tabPanel.add(groupDocsComparison.panel);
		tabPanel.activate("groupdocs_comparison_plugin_tab_panel");

		pimcore.layout.refresh();
	}, 
	saveClick : function () {
		var cid = Ext.getCmp('cid').getValue();
		var pkey = Ext.getCmp('pkey').getValue();
		var firstfileid = Ext.getCmp('firstfileid').getValue();
		var secondfileid = Ext.getCmp('secondfileid').getValue();
		var frameborder = Ext.getCmp('frameborder').getValue();
		var width = Ext.getCmp('width').getValue();
		var height = Ext.getCmp('height').getValue();
        Ext.Ajax.request({
					url : '/plugin/GroupDocsComparison/group-docs-comparison-admin/savedata',
					params: {
						'cid' : cid,
						'pkey' : pkey,
						'firstfileid' : firstfileid,
						'secondfileid' : secondfileid,
						'frameborder' : frameborder,
						'width' : width,
						'height' : height
					},
					success : function(response, options) {
                        Ext.MessageBox.show({
                                    title : 'GroupDocs Plugin',
                                    msg : 'Operation complete!',
                                    buttons : Ext.MessageBox.OK,
                                    animateTarget : 'mb9',
                                    icon : Ext.MessageBox.SUCCESS
                                });
					},
					failure : function(response, options) {
						Ext.MessageBox.show({
									title : 'GroupDocs Plugin Error',
									msg : 'GroupDocs Plugin Error - can\'t save data!',
									buttons : Ext.MessageBox.OK,
									animateTarget : 'mb9',
									icon : Ext.MessageBox.ERROR
								});
					}
				});
	}
});
var groupDocsComparison = new pimcore.plugin.GroupDocsComparison();