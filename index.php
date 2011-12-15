<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Todo</title>
    <link rel="stylesheet" type="text/css" href="ext/resources/css/ext-all.css" />
    <link rel="stylesheet" type="text/css" href="style/style.css" />
    <script type="text/javascript" src="ext/bootstrap.js"></script>
    <script type="text/javascript" >
Ext.Loader.setConfig({
    enabled: true
});
Ext.Loader.setPath('Ext.ux', 'ext/examples/ux');

Ext.require([
    'Ext.selection.CellModel',
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.util.*',
    'Ext.state.*',
    'Ext.form.*',
    'Ext.ux.CheckColumn',
    'Ext.toolbar.Paging',
]);

Ext.onReady(function(){

	function renderStrike(value,p,rec,row) {
		var roffs = 30;
		if (rec.data.done==1)
			value = '<div class="done">'+value+'</div>';
		return value;
	}

    Ext.define('Todo', {
        extend: 'Ext.data.Model',
        fields: [
			  {name: 'id', type: 'integer'},{name: 'text'},{name: 'done', type: 'bool'}
        ]
    });


    // create the Data Store
    var store = Ext.create('Ext.data.Store', {
    	remoteSort: true,
        pageSize: 10,
        autoDestroy: true,
        model: 'Todo',
        proxy: {
            type: 'ajax',
            // load remote data using HTTP
            url: 'view.php',
            // specify a XmlReader (coincides with the XML format of the returned data)
            reader: {
                type: 'xml',
                // records will have a 'Todo' tag
                record: 'item'
            }, 
			writer: {
				type: 'xml'
			}
        },
        sorters: [{
            property: 'id',
            direction:'ASC'
        }]
    });
	
	
	
	// manually trigger the data store load
    //store.load();
    store.loadPage(1);

    // create the grid and specify what field you want
    // to use for the editor at each header.
     grid = Ext.create('Ext.grid.Panel', {
        store: store,
        columns: [
			{
				header: 'id',
				dataIndex: 'id',
				width: 30,
				renderer: renderStrike
            },
			{
				header: 'text',
				dataIndex: 'text',
				flex: 1,
				renderer: renderStrike,
				editor: {}
            },
			{
				header: 'done',
	            dataIndex: 'done',
    	        editor: {
					xtype: 'checkbox'	
				},
				xtype: 'booleancolumn',
				trueText: 'Yes',
	            falseText: 'No',
				align: 'center'
       	},
       	{
	        header: 'actions',
	        dataIndex: 'id',
	        width: 50,
	        align: 'center',
	        renderer: function(value) {
	            return '<a href="#" class="delete" onclick="Ext.Ajax.request({url: \'delete.php\',params: {id:'+value+'},success: function(response){console.log(response);grid.getStore().load();}});return false;">Delete</a>';
	        }
	    }
       	],
        selModel: {
        	selType: 'cellmodel'
        },
        renderTo: 'editor-grid',
        width: '100%',
        height: 400,
        title: 'ToDo List',
        frame: true,
        plugins: [
			Ext.create('Ext.grid.plugin.CellEditing', {clicksToEdit: 2})
		],
		// paging bar on the bottom
        bbar: Ext.create('Ext.PagingToolbar', {
            store: store,
            displayInfo: true,
            displayMsg: 'Displaying topics {0} - {1} of {2}',
            emptyMsg: "No topics to display"
        })
        
    });

	grid.on('edit', function(editor, e) {
		Ext.Ajax.request({
			url: 'edit.php',
			params: {
				id: e.record.data.id,
				column: e.field,
				value: e.value ==true ? 1 : e.value==false ? 0 : e.value
			}
		});
		// commit the changes right after editing finished
		e.record.commit();
	});
	
	Ext.create('Ext.form.Panel', {
		title: 'Add new Todo item',
		bodyPadding: 5,
		width: '100%',
	
		// The form will submit an AJAX request to this URL when submitted
		url: 'insert.php',
	
		// Fields will be arranged vertically, stretched to full width
		layout: 'anchor',
		defaults: {
			anchor: '100%'
		},
	
		// The fields
		defaultType: 'textfield',
		items: [{
			fieldLabel: 'Do',
			name: 'text',
			allowBlank: false
		}
		],
	
		// Reset and Submit buttons
		buttons: [{
			text: 'Add New',
			formBind: true, //only enabled once the form is valid
			handler: function() {
				var form = this.up('form').getForm();
				if (form.isValid()) {
					form.submit({
						success: function(form, action) {
						   Ext.Msg.alert('Success', action.result.msg);
						   form.reset();
						   store.load();
						},
						failure: function(form, action) {
							Ext.Msg.alert('Failed', action.result.msg);
						}
					});
				}
			}
		}],
		renderTo: 'insert-form'
	});
	
	
});
    </script>
</head>
<body>
	<div class="container" >
		<div id="insert-form" ></div><br/>
	    <div id="editor-grid"></div>
    </div>
</body>
</html>