( function() {
   tinymce.PluginManager.add( 'cool_timeline', function( editor, url ) {
		editor.on( 'keyup' , function() {
				if ( editor.getContent().indexOf( '[cool-timeline]' ) > -1 ) {
					editor.controlManager.setDisabled('cool_timeline_shortcode_button', true);
				} else {
					editor.controlManager.setDisabled('cool_timeline_shortcode_button', false);
				}
			});

	   var layouts=[];
	   layouts.push({"text":'Default Layout',"value":'default'});
	   layouts.push({"text":'One Side Layout',"value":'one-side'});
	   var skins=[];
	   skins.push({"text":"default","value":"default"});
	   skins.push({"text":"light","value":"light"});
	   skins.push({"text":"dark","value":"dark"});

	   var $s_order=[];
	   $s_order.push({"text":"DESC","value":"DESC"});
	   $s_order.push({"text":"ASC","value":"ASC"});

	   var $icons_options=[];
	   $icons_options.push({"text":"NO","value":"NO"});
	   $icons_options.push({"text":"YES","value":"YES"});
	   var ctl_cats=JSON.parse(my_plugin.category);
	   var categories=[];

	   for( var cat in ctl_cats){

		   categories.push({"text":ctl_cats[cat],"value":cat});
	   }

      
        editor.addButton( 'cool_timeline_shortcode_button', {
		
				text: false,
				type: 'menubutton',
				image: url + '/cooltimeline.png',
		
				menu: [
                {
                    text: 'Default',
					onclick: function() {

						editor.windowManager.open( {
							title: 'Add Cool Timeline Shortcode',
							body: [
								{
									type: 'textbox',
									name: 'number_of_posts',
									label: 'Show number of posts',
									value:20
								},
								{
									type: 'listbox',
									name: 'timeline_layout',
									label: 'Timeline Layout',
									'values':layouts
								},
								{
									type: 'listbox',
									name: 'timeline_skin',
									label: 'Timeline skin',
									'values':skins
								},
								{
									type: 'listbox',
									name: 'stories_order',
									label: 'Story Order',
									'values':$s_order
								},
								{
									type: 'listbox',
									name: 'ctl_icons',
									label: 'Icons',
									'values':$icons_options
								}
							],
							onsubmit: function( e ) {
								editor.insertContent( '[cool-timeline layout="'+ e.data.timeline_layout+'"  skin="'+ e.data.timeline_skin+'" show-posts="' + e.data.number_of_posts + '" order="' + e.data.stories_order + '"  icons="' + e.data.ctl_icons + '"]');
							}
						});
					}
                },
                {
                    text: 'Categories timeline',
                    onclick: function() {

                        editor.windowManager.open( {
                            title: 'Add Cool Timeline Shortcode',
                            body: [
							{
                                type: 'textbox',
                                name: 'number_of_posts',
                                label: 'Show number of posts',
								value:20
                            },
							{
                                type: 'listbox', 
                                name: 'category', 
                                label: 'Stories categoires', 
                                'values':categories
                            },
							{
								type: 'listbox',
								name: 'timeline_layout',
								label: 'Timeline Layout',
								'values':layouts
							},
							{
								type: 'listbox',
								name: 'timeline_skin',
								label: 'Timeline skin',
								'values':skins
							},
							{
								type: 'listbox',
								name: 'stories_order',
								label: 'Story Order',
								'values':$s_order
							},
								{
									type: 'listbox',
									name: 'ctl_icons',
									label: 'Icons',
									'values':$icons_options
								}
							],
                            onsubmit: function( e ) {
                                editor.insertContent( '[cool-timeline layout="'+ e.data.timeline_layout+'" skin="'+ e.data.timeline_skin+'" category="' + e.data.category + '" show-posts="' + e.data.number_of_posts + '" order="' + e.data.stories_order + '" icons="' + e.data.ctl_icons + '" ]');
                            }
                        });
                    }
                },

				{
						text: 'Horizontal Timeline',
						onclick: function() {

							editor.windowManager.open( {
								title: 'Add Cool Timeline Shortcode',
								body: [
									{
										type: 'listbox',
										name: 'category',
										label: 'Stories categoires',
										'values':categories
									},
									{
										type: 'textbox',
										name: 'number_of_posts',
										label: 'Show number of posts',
										value:20
									},
									{
										type: 'listbox',
										name: 'timeline_skin',
										label: 'Timeline skin',
										'values':skins
									},
									{
										type: 'listbox',
										name: 'stories_order',
										label: 'Story Order',
										'values':$s_order
									},
									{
										type: 'listbox',
										name: 'ctl_icons',
										label: 'Icons',
										'values':$icons_options
									}
								],
								onsubmit: function( e ) {
									editor.insertContent( '[cool-timeline type="horizontal" category="' + e.data.category + '" skin="'+ e.data.timeline_skin+'" show-posts="' + e.data.number_of_posts + '" order="' + e.data.stories_order + '"  icons="' + e.data.ctl_icons + '"]');
								}
							});
						}
					},
					{
						text: 'Content Timeline',
						onclick: function() {

							editor.windowManager.open( {
								title: 'Add Cool Timeline Shortcode',
								body: [
									{
										type: 'textbox',
										name: 'post_type',
										label: 'Content Post type',
										value:'post'
									},
									{
										type: 'textbox',
										name: 'number_of_posts',
										label: 'Show number of posts',
										value:20
									},
									{
										type: 'listbox',
										name: 'timeline_layout',
										label: 'Timeline Layout',
										'values':layouts
									},
									{
										type: 'listbox',
										name: 'timeline_skin',
										label: 'Timeline skin',
										'values':skins
									},
									{
										type: 'listbox',
										name: 'stories_order',
										label: 'Story Order',
										'values':$s_order
									},
									{
										type: 'listbox',
										name: 'ctl_icons',
										label: 'Icons',
										'values':$icons_options
									}
								],
								onsubmit: function( e ) {
									editor.insertContent( '[cool-timeline post-type="'+ e.data.post_type+'" layout="'+ e.data.timeline_layout+'"  skin="'+ e.data.timeline_skin+'" show-posts="' + e.data.number_of_posts + '" order="' + e.data.stories_order + '"  icons="' + e.data.ctl_icons + '"]');
								}
							});
						}
					}
           ]
			});
		editor.onSetContent.add(function(editor, o) {
			  if ( editor.getContent().indexOf( '[cool-timeline]' ) > -1) {
					editor.controlManager.setDisabled('cool_timeline_shortcode_button', true);
				}
		  });

	
	});
	

})();