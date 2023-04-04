( function() {
	var __                = wp.i18n.__; // The __() function for internationalization.
	var _x                = wp.i18n._x; // The _x() function for internationalization.
	var createElement     = wp.element.createElement; // The wp.element.createElement() function to create elements.
	var registerBlockType = wp.blocks.registerBlockType; // The registerBlockType() function to register blocks.
	var RichText          = wp.blockEditor.RichText; // For creating editable elements.
	var InnerBlocks       = wp.blockEditor.InnerBlocks; // For creating editable elements.
	var InspectorControls = wp.blockEditor.InspectorControls; // For adding block controls.
	var BlockControls 	  = wp.blockEditor.BlockControls;
	var BlockVerticalAlignmentToolbar 	  = wp.blockEditor.BlockVerticalAlignmentToolbar;
	var PanelColorSettings= wp.blockEditor.PanelColorSettings;
	var withColors 		  = wp.blockEditor.withColors;
	
	var AlignmentToolbar  = wp.blockEditor.AlignmentToolbar; // For creating the alignment toolbar element within the control elements.
	var PanelBody     	  = wp.components.PanelBody;
	var RangeControl 	  =	wp.components.RangeControl;
	var ToggleControl 	  =	wp.components.ToggleControl;
	var TextControl 	  =	wp.components.TextControl;
	var SelectControl 	  =	wp.components.SelectControl;
	var ExternalLink 	  =	wp.components.ExternalLink;
	var FocalPointPicker  = wp.components.FocalPointPicker;
	var Toolbar			  = wp.components.Toolbar;
	var TextareaControl   = wp.components.TextareaControl;
	var ColorPalette 	  =	wp.blockEditor.ColorPalette;
		
	const SLICKSLIDER = [['core/group']];
	const SLICKSLIDER_ALLOWED = ['core/group','woocommerce/featured-product','woocommerce/handpicked-products','woocommerce/product-best-sellers','woocommerce/product-new','woocommerce/product-on-sale','woocommerce/product-tag','woocommerce/product-top-rated','woocommerce/products-by-attribute'];
		
	const ALLOWED_BLOCKS = [ 'core/paragraph','core/heading','core/list','core/image','core/button'];
	const IMAGEHEADING = [
		['core/image'],
		['core/column',{}, [
			['core/heading', { placeholder: 'Heading' }],
			[ 'core/paragraph', { fontSize: 'default', placeholder: _x( 'Lorem ipsum dolor mit…', 'content placeholder' ) } ],
		]],
	];
	
	var blockStyle = {
		backgroundColor: '#f8f8f8',
		padding: '10px',
		border:'1px solid #999',
	};
	
	var simpleStyle = {
		backgroundColor: 'transparent',
		padding: '10px',
		border:'1px solid #fff',
	};
	
	var numberStyle = {
		fontSize:'60px',
		textAlign:'center',
	};
	
	var descriptionStyle = {
		textAlign:'center',
	};
	
		/**
	 * Register block
	 *
	 * @param  {string}   name     Block name.
	 * @param  {Object}   settings Block settings.
	 * @return {?WPBlock}          Block itself, if registered successfully,
	 *                             otherwise "undefined".
	 */
	registerBlockType(
		'pep/breadcrumbs', // Block name. Must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
		{
			title: __( 'Breadcrumbs' ), // Block title. __() function allows for internationalization.
			description: __( 'Shows Yoast breadcrumbs on frontend.','pep' ), // Block description.
			icon: 'share-alt2', // Block icon from Dashicons. https://developer.wordpress.org/resource/dashicons/.
			category: 'common', // Block category. Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
			supports: {
				align:['full','wide']
			},
			attributes: {
				content: {
		            type: 'array',
		            selector: 'div',
				},
				align: {
					type: 'string',
					default: ''
				},
			},

			// Defines the block within the editor.
			edit: function( props ) {

				const controls = [
					
				];				

				return [
					controls,
					createElement(
						'div',
						{ style: blockStyle },
						__( 'Shows Yoast breadcrumbs on frontend.','pep' )
					),
				];
			},

			// Defines the saved block.
			save: function() {
				return null;
			},
		}
	);


	/**
	 * Register block
	 *
	 * @param  {string}   name     Block name.
	 * @param  {Object}   settings Block settings.
	 * @return {?WPBlock}          Block itself, if registered successfully,
	 *                             otherwise "undefined".
	 */
	registerBlockType(
		'pep/clients', // Block name. Must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
		{
			title: __( 'Clients' ), // Block title. __() function allows for internationalization.
			description: __( 'Shows carousel with client logos on frontend.' ), // Block description.
			icon: 'businessman', // Block icon from Dashicons. https://developer.wordpress.org/resource/dashicons/.
			category: 'layout', // Block category. Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
			supports: {
				align:['full','wide']
			},
			attributes: {
				content: {
		            type: 'array',
		            selector: 'div',
				},
				align: {
					type: 'string',
					default: ''
				},
				load_slides: {
					type: 'integer',
					default: '1'
				},
				slides_to_scroll: {
					type: 'integer',
					default: '1'
				},
				show_arrows: {
					type: 'bool',
					default: false
				},				
				show_controls: {
					type: 'bool',
					default: false
				},
				infinite: {
					type: 'bool',
					default: false
				},	
				show_text: {
					type: 'bool',
					default: false
				},				
				show_image: {
					type: 'bool',
					default: false
				},
				appendArrows: {
					type: 'string',
					default: ''
				},
				prevArrow: {
					type: 'string',
					default: 'Vorige'
				},
				nextArrow: {
					type: 'string',
					default: 'Volgende'
				},
				appendDots: {
					type: 'string',
					default: ''
				},
				auto_play: {
					type: 'bool',
					default: false
				},
				speed: {
					type: 'integer',
					default:5000,
				},
				auto_play_speed: {
					type: 'integer',
					default:5000,
				},
				types: {
					type: 'string',
					default: ''
				},
			},

			// Defines the block within the editor.
			edit: function( props ) {

				const controls = [
					createElement(
						InspectorControls,
						{},
						createElement(
							PanelBody,
							{
							}
						),
						createElement(
							RangeControl, {
								label: 'Columns',
								value: props.attributes.load_slides,
								initialPosition: 1,
								min: 1,
								max: 10,
								onChange: function( val ) {
									props.setAttributes({ load_slides: val })
								}
							}
						),
						createElement(
							RangeControl, {
								label: 'Slides to scroll',
								value: props.attributes.slides_to_scroll,
								initialPosition: 1,
								min: 1,
								max: 10,
								onChange: function( val ) {
									props.setAttributes({ slides_to_scroll: val })
								}
							}
						),
						createElement(
							ToggleControl, {
								label: 'Show prev/next arrows',
								help: '',
								value:props.attributes.show_arrows,
								checked: props.attributes.show_arrows,
								onChange: function( val ) {
									props.setAttributes({ show_arrows: val })
								}
							}
						),
						createElement(
							TextControl, {
								label: 'Append arrows',
								value: props.attributes.appendArrows,
								type:'text',
								help:'Append arrows to element, enter CSS-class name of element',
								onChange: function( val ) {
									props.setAttributes({ appendArrows: val })
								}
							}
						),
						createElement(
							TextControl, {
								label: 'Text previous arrow',
								value: props.attributes.prevArrow,
								type:'text',
								onChange: function( val ) {
									props.setAttributes({ prevArrow: val })
								}
							}
						),
						createElement(
							TextControl, {
								label: 'Text next arrow',
								value: props.attributes.nextArrow,
								type:'text',
								onChange: function( val ) {
									props.setAttributes({ nextArrow: val })
								}
							}
						),
						createElement(
							ToggleControl, {
								label: 'Show controls (bullets)',
								help: '',
								value: props.attributes.show_controls,
								checked: props.attributes.show_controls,
								onChange: function( val ) {
									props.setAttributes({ show_controls: val })
								}
							}
						),
						createElement(
							TextControl, {
								label: 'Append bullets',
								value: props.attributes.appendDots,
								type:'text',
								help:'Append bullets to element, enter CSS-class name of element',
								onChange: function( val ) {
									props.setAttributes({ appendDots: val })
								}
							}
						),
						createElement(
							ToggleControl, {
								label: 'Infinite loop',
								help: '',
								value: props.attributes.infinite,
								checked: props.attributes.infinite,
								onChange: function( val ) {
									props.setAttributes({ infinite: val })
								}
							}
						),
						createElement(
							ToggleControl, {
								label: 'Show text',
								help: '',
								value:props.attributes.show_text,
								checked: props.attributes.show_text,
								onChange: function( val ) {
									props.setAttributes({ show_text: val })
								}
							}
						),
						createElement(
							ToggleControl, {
								label: 'Show image',
								help: '',
								value:props.attributes.show_image,
								checked: props.attributes.show_image,
								onChange: function( val ) {
									props.setAttributes({ show_image: val })
								}
							}
						),
						createElement(
							ToggleControl, {
								label: 'Auto play',
								help: '',
								value:props.attributes.auto_play,
								checked: props.attributes.auto_play,
								onChange: function( val ) {
									props.setAttributes({ auto_play: val })
								}
							}
						),
						createElement(
								RangeControl, {
									label: 'Auto Play Speed',
									value: props.attributes.auto_play_speed,
									initialPosition: 5000,
									min: 0,
									max: 10000,
									step:100,
									onChange: function( val ) {
										props.setAttributes({ auto_play_speed: val })
									}
								}
						),
						createElement(
								RangeControl, {
									label: 'Speed',
									value: props.attributes.speed,
									initialPosition: 5000,
									min: 0,
									max: 10000,
									step:100,
									onChange: function( val ) {
										props.setAttributes({ speed: val })
									}
								}
						),
						createElement(
							TextControl, {
								label: 'Types',
								value: props.attributes.types,
								type:'text',
								help:'Enter ID of type tags separated with comma.',
								onChange: function( val ) {
									props.setAttributes({ types: val })
								}
							}
						),
						
					),
				];				

				return [
					controls,
					createElement(
						'div',
						{ style: blockStyle },
						'Shows carousel with client logos on frontend.'
					),
				];
			},

			// Defines the saved block.
			save: function() {
				return null;
			},
		}
	);
	
	/**
	 * Register block
	 *
	 * @param  {string}   name     Block name.
	 * @param  {Object}   settings Block settings.
	 * @return {?WPBlock}          Block itself, if registered successfully,
	 *                             otherwise "undefined".
	 */
	registerBlockType(
		'pep/countup', // Block name. Must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
		{
			title: __( 'Stats counter' ), // Block title. __() function allows for internationalization.
			description: __( 'Showcase your stats.','pep' ), // Block description.
			icon: 'chart-pie', // Block icon from Dashicons. https://developer.wordpress.org/resource/dashicons/.
			category: 'common', // Block category. Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
			supports: {
				align:['full','wide']
			},
			attributes: {
				number: {
		            type: 'string',
					default:'100',
		            selector: 'div',
				},
				description: {
		            type: 'string',
					default:'Description',
		            selector: 'div',
				},
				start: {
					type: 'string',
					default:0,
				},
				end: {
					type: 'string',
					default:100,
				},
				speed: {
					type: 'integer',
					default:5000,
				},
				digits: {
					type: 'string',
					default:1,
				},
				decimals: {
					type: 'string',
					default:0,
				},
				decimalDelimiter: {
					type: 'string',
					default:',',
				},
				thousandDelimiter: {
					type: 'string',
					default:'.',
				},
				prefix: {
					type: 'string',
					default:'',
				},
				suffix: {
					type: 'string',
					default:'%',
				},
				circleColor: {
					type: 'string',
					default:'#000000',
				},
				textColor: {
					type: 'string',
					default:'#000000',
				}
			},

			// Defines the block within the editor.
			edit: function( props ) {
				var number= props.attributes.number;
				var description= props.attributes.description;
				var focus = props.focus;
				
				function onChangeNumber( updatedContent ) {
					props.setAttributes( { number: updatedContent } );
				}
				function onChangeDescription( updatedContent ) {
					props.setAttributes( { description: updatedContent } );
				}
				

				const controls = [
					createElement(
						InspectorControls,
						{},
						createElement(
							PanelBody,
							{
								title:'Default settings',
							},createElement(
								TextControl, {
									label: 'Start number',
									value: props.attributes.start,
									type:'number',
									onChange: function( val ) {
										props.setAttributes({ start: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'End number',
									value: props.attributes.end,
									type:'number',
									onChange: function( val ) {
										props.setAttributes({ end: val })
									}
								}
							),
							createElement(
								RangeControl, {
									label: 'Speed',
									value: props.attributes.speed,
									initialPosition: 5000,
									min: 0,
									max: 10000,
									step:100,
									onChange: function( val ) {
										props.setAttributes({ speed: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Digits',
									value: props.attributes.digits,
									help:'How many digits would you like to show?',
									type:'number',
									min: 0,
									max: 5,
									step: 1,
									onChange: function( val ) {
										props.setAttributes({ digits: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Decimals',
									value: props.attributes.decimals,
									help:'How many decimals?',
									type:'number',
									min: 0,
									max: 5,
									step: 1,
									onChange: function( val ) {
										props.setAttributes({ decimals: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Decimal delimiter',
									value: props.attributes.decimalDelimiter,
									type:'text',
									onChange: function( val ) {
										props.setAttributes({ decimalDelimiter: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Thousand delimiter',
									value: props.attributes.thousandDelimiter,
									type:'text',
									onChange: function( val ) {
										props.setAttributes({ thousandDelimiter: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Prefix',
									value: props.attributes.prefix,
									type:'text',
									help:'Show text BEFORE number',
									onChange: function( val ) {
										props.setAttributes({ prefix: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Suffix',
									value: props.attributes.suffix,
									type:'text',
									help:'Show text AFTER number',
									onChange: function( val ) {
										props.setAttributes({ suffix: val })
									}
								}
							),
						),
						createElement(
							PanelBody,
							{
								title:'Circle background color',
							},
							createElement(
								ColorPalette, {
									label: 'Circle background color',
									value: props.attributes.circleColor,
									onChange: function( val ) {
										props.setAttributes({ circleColor: val })
									}
								}
							),
						),
						
						createElement(
							PanelBody,
							{
								title:'Text & Circle color',
							},
							createElement(
								ColorPalette, {
									label: 'Text & Circle color',
									value: props.attributes.textColor,
									onChange: function( val ) {
										props.setAttributes({ textColor: val })
									}
								}
							),
						),
						
					),
				];				

				return [
					controls,
					createElement(
						RichText,
						{
							tagName:'div',
							style: numberStyle,
							className: props.className,						
							value: number,
							onChange: onChangeNumber,
							focus:focus,
							onFocus:props.setFocus,
						},
						//__( 'Number','pep' )
					),
					createElement(
						RichText,
						{
							tagName:'div',
							style: descriptionStyle,
							className: props.className,						
							value: description,
							onChange: onChangeDescription,
							focus:focus,
							onFocus:props.setFocus,
						},
						//__( 'Description','pep' )
					)
				];
			},

			// Defines the saved block.
			save: function( props ) {
				var number = props.attributes.number;
				var description = props.attributes.description;
				return [createElement(
						RichText,
						{
							tagName:'div',					
							'value': number,
						},
						//__( 'Number','pep' )
					),
					createElement(
						RichText,
						{
							'tagName':'div',					
							'value': description,							
						},
						//__( 'Description','pep' )
					)]
			},
		}
	);
		
	/**
	 * Register block
	 *
	 * @param  {string}   name     Block name.
	 * @param  {Object}   settings Block settings.
	 * @return {?WPBlock}          Block itself, if registered successfully,
	 *                             otherwise "undefined".
	 */
	registerBlockType(
		'pep/imageheader', // Block name. Must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
		{
			title: __( 'Image & Heading' ), // Block title. __() function allows for internationalization.
			description: __( 'Show image + heading with possible link','pep' ), // Block description.
			icon: 'id-alt', // Block icon from Dashicons. https://developer.wordpress.org/resource/dashicons/.
			category: 'layout', // Block category. Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
			supports: {
				align:['full','wide']
			},
			attributes: {
				urlLink: {
					type: 'string',
				},
				type: {
					type: 'string',
					default:'default',
				},
			},
			//Edit
			edit: function( props ) {
				const controls = [
					createElement(
						InspectorControls,
						{},
						createElement(
							PanelBody,
							{
								title:'Default settings',
							},createElement(
								TextControl, {
									label: 'Url to page',
									value: props.attributes.urlLink,
									type:'text',
									help:'Define URL to page, starting with https://',
									onChange: function( val ) {
										props.setAttributes({ urlLink: val })
									}
								}
							),createElement(
								SelectControl, {
									label: 'Type',
									value: props.attributes.type,
									help:'Select which style / type you want to show',
									options:[
										{label: 'Default', value:'default'},
										{label: 'Image right', value:'image-right'},
										{label: 'Image left', value:'image-left'},
									],
									onChange: function( val ) {
										props.setAttributes({ type: val })
									}
								}
							),
							
						),
					),
				];	
		
        return [
			controls,
            createElement('div', { className: props.className,style:blockStyle},
                   createElement(
						InnerBlocks,
						{
							template:IMAGEHEADING,
							//templateLock:'false',
							allowedBlocks: ALLOWED_BLOCKS,
						},						
					),
                )

        ];
    },

    //Save
    save: function( props ) {
		var content=InnerBlocks.Content;
			if(typeof props.attributes.urlLink !== 'undefined' && props.attributes.urlLink!="" && props.attributes.urlLink!=" ") {
				return (
					createElement('div', {className: props.className},
						createElement('a', { href:props.attributes.urlLink,className: props.attributes.type }, //Need add props.className to render saved content
							createElement( content, null )
						)
					)
				);
			} else {
				return (
					createElement('div', {className: props.className},
						createElement('div', { className: props.attributes.type}, //Need add props.className to render saved content
							createElement( content, null )
						)
					)
				);
			}
    }
			
		}
	);

	
	
	/**
	 * Register block
	 *
	 * @param  {string}   name     Block name.
	 * @param  {Object}   settings Block settings.
	 * @return {?WPBlock}          Block itself, if registered successfully,
	 *                             otherwise "undefined".
	 */
	registerBlockType(
		'pep/richcontact', // Block name. Must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
		{
			title: __( 'Contact info' ), // Block title. __() function allows for internationalization.
			description: __( 'Rich snippet contact info (schema.org)','pep' ), // Block description.
			icon: 'nametag', // Block icon from Dashicons. https://developer.wordpress.org/resource/dashicons/.
			category: 'layout', // Block category. Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
			supports: {},
			attributes: {
				company_name: {
					type: 'string',
					default: '',
				},
				subline: {
					type: 'string',
					default: '',
				},
				street: {
					type: 'string',
					default: '',
				},
				postcode: {
					type: 'string',
					default: '',
				},
				city: {
					type: 'string',
					default: '',
				},
				country: {
					type: 'string',
					default: 'NL',
				},
				phone: {
					type: 'string',
					default: '',
				},
				fax: {
					type: 'string',
					default: '',
				},
				email: {
					type: 'string',
					default: '',
				},		
				alt_url: {
					type: 'string',
					default: '',
				},
				description: {
					type: 'string',
					default: '',
				},
				company_vat: {
					type: 'string',
					default: '',
				},
				company_kvk: {
					type: 'string',
					default: '',
				},
				schema_org: {
					type: 'string',
					default: 'LocalBusiness',
				},
				price_range: {
					type: 'string',
					default: '$$',
				},				
				show_name: {
					type: 'bool',
					default: true
				},
				show_subline: {
					type: 'bool',
					default: false
				},
				show_country: {
					type: 'bool',
					default: false
				},
				show_vat: {
					type: 'bool',
					default: false
				},
				show_phone: {
					type: 'bool',
					default: true
				},
				show_fax: {
					type: 'bool',
					default: false
				},
				show_email: {
					type: 'bool',
					default: true
				},
				show_kvk: {
					type: 'bool',
					default: false
				},
				show_hours: {
					type: 'bool',
					default: false
				},
				show_menu: {
					type: 'bool',
					default: false
				},
				show_reservations: {
					type: 'bool',
					default: false
				},
				show_map: {
					type: 'bool',
					default: false
				},
				hide_all_map: {
					type: 'bool',
					default: false
				},
				full_days: {
					type: 'bool',
					default: false
				},
				opening_Mo_open: {
					type: 'string',
					default: '',
				},
				opening_Mo_close: {
					type: 'string',
					default: '',
				},
				opening_Tu_open: {
					type: 'string',
					default: '',
				},
				opening_Tu_close: {
					type: 'string',
					default: '',
				},
				opening_We_open: {
					type: 'string',
					default: '',
				},
				opening_We_close: {
					type: 'string',
					default: '',
				},
				opening_Th_open: {
					type: 'string',
					default: '',
				},
				opening_Th_close: {
					type: 'string',
					default: '',
				},
				opening_Fr_open: {
					type: 'string',
					default: '',
				},
				opening_Fr_closed: {
					type: 'string',
					default: '',
				},
				opening_Sa_open: {
					type: 'string',
					default: '',
				},
				opening_Sa_close: {
					type: 'string',
					default: '',
				},
				opening_Su_open: {
					type: 'string',
					default: '',
				},
				opening_Su_close: {
					type: 'string',
					default: '',
				},
				company_menu: {
					type: 'string',
					default: '',
				},
				company_reservations: {
					type: 'string',
					default: '',
				},
				block_id: {
					type: 'string',
					default: '',
				},
			},
			//Edit
			edit: function( props ) {
				var company_name= props.attributes.company_name;
				var subline= props.attributes.subline;
				var street= props.attributes.street;
				var postcode= props.attributes.postcode;
				var city= props.attributes.city;				
				var phone= props.attributes.phone;
				var email= props.attributes.email;
				var focus = props.focus;
				
				
				var post_id=wp.data.select('core/editor').getCurrentPostId();
				
				var randomNum=Math.round(Math.random() * 10);
				
				if(props.attributes.block_id=="") {
					props.setAttributes({ block_id: randomNum });
				}
				const controls = [
					createElement(
						InspectorControls,
						{},
						createElement(
							PanelBody,
							{
								title:'Default settings',
								initialOpen:false,
							},
							createElement(
								ToggleControl, {
									label: 'Show company name',
									help: '',
									value:props.attributes.show_name,
									checked: props.attributes.show_name,
									onChange: function( val ) {
										props.setAttributes({ show_name: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Show subline',
									help: 'Show extra information below company name. For example: "No visiting address"',
									value:props.attributes.show_subline,
									checked: props.attributes.show_subline,
									onChange: function( val ) {
										props.setAttributes({ show_subline: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Show country',
									help: '',
									value:props.attributes.show_country,
									checked: props.attributes.show_country,
									onChange: function( val ) {
										props.setAttributes({ show_country: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Show phone number',
									help: '',
									value:props.attributes.show_phone,
									checked: props.attributes.show_phone,
									onChange: function( val ) {
										props.setAttributes({ show_phone: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Show fax number',
									help: '',
									value:props.attributes.show_fax,
									checked: props.attributes.show_fax,
									onChange: function( val ) {
										props.setAttributes({ show_fax: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Show email',
									help: '',
									value:props.attributes.show_email,
									checked: props.attributes.show_email,
									onChange: function( val ) {
										props.setAttributes({ show_email: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Show map',
									help: '',
									value:props.attributes.show_map,
									checked: props.attributes.show_map,
									onChange: function( val ) {
										props.setAttributes({ show_map: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Hide all except map',
									help: '',
									value:props.attributes.hide_all_map,
									checked: props.attributes.hide_all_map,
									onChange: function( val ) {
										props.setAttributes({ hide_all_map: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Show VAT number',
									help: '',
									value:props.attributes.show_vat,
									checked: props.attributes.show_vat,
									onChange: function( val ) {
										props.setAttributes({ show_vat: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Show Chamber of commerce number',
									help: '',
									value:props.attributes.show_kvk,
									checked: props.attributes.show_kvk,
									onChange: function( val ) {
										props.setAttributes({ show_kvk: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Show Opening hours',
									help: '',
									value:props.attributes.show_hours,
									checked: props.attributes.show_hours,
									onChange: function( val ) {
										props.setAttributes({ show_hours: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Show full day names',
									help: 'If disabled show only abbr.',
									value:props.attributes.full_days,
									checked: props.attributes.full_days,
									onChange: function( val ) {
										props.setAttributes({ full_days: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Show link to menu page',
									help: 'Restaurants only',
									value:props.attributes.show_menu,
									checked: props.attributes.show_menu,
									onChange: function( val ) {
										props.setAttributes({ show_menu: val })
									}
								}
							),
							createElement(
								ToggleControl, {
									label: 'Show link to reservation page',
									help: 'Restaurants only',
									value:props.attributes.show_reservations,
									checked: props.attributes.show_reservations,
									onChange: function( val ) {
										props.setAttributes({ show_reservations: val })
									}
								}
							),
						),
						createElement(
							PanelBody,
							{
								title:'Company information',
								initialOpen:false,
							},
							createElement(
								SelectControl, {
									label: 'Company type',
									value: props.attributes.schema_org,
									help:'Select company type for this company (default: Local Business)',
									options:[
										{value:"LocalBusiness",label:"Lokaal bedrijf (standaard)"},
										{value:"ChildCare",label:"ChildCare"},
										{value:"Corporation",label:"Corporation"},
										{value:"EducationalOrganization",label:"Educational Organization"},
										{value:"Bakery",label:"Eetgelegenheid - Bakkerij"},
										{value:"BarOrPub",label:"Eetgelegenheid - Bar of Pub"},
										{value:"Brewery",label:"Eetgelegenheid - Brouwerij"},
										{value:"CafeOrCoffeeShop",label:"Eetgelegenheid - Caf\u00e9 of koffie-tentje"},
										{value:"FastFoodRestaurant",label:"Eetgelegenheid - Fastfood restaurant"},
										{value:"IceCreamShop",label:"Eetgelegenheid - IJswinkel"},
										{value:"Restaurant",label:"Eetgelegenheid - Restaurant"},
										{value:"Winery",label:"Eetgelegenheid - Winery"},
										{value:"GovernmentOrganization",label:"Government Organization"},
										{value:"HealthAndBeautyBusiness",label:"Health and Beauty Business"},
										{value:"BeautySalon",label:"Health and Beauty Business - Beauty Salon"},
										{value:"DaySpa",label:"Health and Beauty Business - Day Spa"},
										{value:"HealthClub",label:"Health and Beauty Business - Health Club"},
										{value:"HairSalon",label:"Health and Beauty Business - Kapsalon"},
										{value:"NailSalon",label:"Health and Beauty Business - Nagelsalon"},
										{value:"MedicalOrganization",label:"Medical Organization"},
										{value:"NGO",label:"Non-governmental Organisation"},
										{value:"PerformingGroup",label:"Performance Group"},
										{value:"Project",label:"Project"},
										{value:"RealEstateAgent",label:"Real Estate Agent"},
										{value:"TravelAgency",label:"Reisbureau"},
										{value:"SportOrganization",label:"Sport Organization"},
										{value:"SportsActivityLocation",label:"Sports Activity Location"},
										{value:"Dentist",label:"Tandarts"},
										{value:"Store",label:"Winkel"},
										{value:"Florist",label:"Winkel - Bloemist"},
										{value:"BookStore",label:"Winkel - Boekwinkel"},
										{value:"ComputerStore",label:"Winkel - Computerwinkel"},
										{value:"PetStore",label:"Winkel - Dierenwinkel"},
										{value:"ElectronicsStore",label:"Winkel - Electronics Store"},
										{value:"BikeStore",label:"Winkel - Fietsenwinkel"},
										{value:"HobbyShop",label:"Winkel - Hobby Shop"},
										{value:"JewelryStore",label:"Winkel - Juwelier"},
										{value:"ClothingStore",label:"Winkel - Kledingwinkel"},
										{value:"MobilePhoneStore",label:"Winkel - Mobile Phone Store"},
										{value:"OfficeEquipmentStore",label:"Winkel - Office Equipment Store"},
										{value:"ShoeStore",label:"Winkel - Schoenenwinkel"},
										{value:"ToyStore",label:"Winkel - Speelgoedwinkel"},
										{value:"GardenStore",label:"Winkel - Tuincentrum"}
									],
									onChange: function( val ) {
										props.setAttributes({ schema_org: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Country name (EN)',
									value: props.attributes.country,
									type:'text',
									help:'Enter country name (English). You can also provide the two-letter ISO 3166-1 alpha-2 country code.',
									onChange: function( val ) {
										props.setAttributes({ country: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Website URL',
									value: props.attributes.alt_url,
									type:'text',
									help:'By default current website address is shown.',
									onChange: function( val ) {
										props.setAttributes({ alt_url: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Fax number',
									value: props.attributes.fax,
									type:'text',
									help:'',
									onChange: function( val ) {
										props.setAttributes({ fax: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Company VAT number',
									value: props.attributes.company_vat,
									type:'text',
									help:'',
									onChange: function( val ) {
										props.setAttributes({ company_vat: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Chamber of Commerce number',
									value: props.attributes.company_kvk,
									type:'text',
									help:'',
									onChange: function( val ) {
										props.setAttributes({ company_kvk: val })
									}
								}
							),
							createElement(
								TextareaControl, {
									label: 'Company description',
									value: props.attributes.description,
									type:'text',
									help:'',
									onChange: function( val ) {
										props.setAttributes({ description: val })
									}
								}
							),
							createElement(
								SelectControl, {
									label: 'Price range',
									value: props.attributes.price_range,
									help:'Select price range for this company.',
									options:[
										{label: 'Low', value:'$'},
										{label: 'Medium', value:'$$'},
										{label: 'High', value:'$$$'},
									],
									onChange: function( val ) {
										props.setAttributes({ price_range: val })
									}
								}
							),
						),
						createElement(
							PanelBody,
							{
								title:'Opening hours',
								initialOpen:false,
							},
							createElement(
								TextControl, {
									label: 'Monday (open)',
									placeholder:'9:00',
									value: props.attributes.opening_Mo_open,
									onChange: function( val ) {
										props.setAttributes({ opening_Mo_open: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Monday (closed)',
									placeholder:'18:00',
									value: props.attributes.opening_Mo_close,
									onChange: function( val ) {
										props.setAttributes({ opening_Mo_close: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Tuesday (open)',
									placeholder:'9:00',
									value: props.attributes.opening_Tu_open,
									onChange: function( val ) {
										props.setAttributes({ opening_Tu_open: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Tuesday (closed)',
									placeholder:'18:00',
									value: props.attributes.opening_Tu_close,
									onChange: function( val ) {
										props.setAttributes({ opening_Tu_close: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Wednesday (open)',
									placeholder:'9:00',
									value: props.attributes.opening_We_open,
									onChange: function( val ) {
										props.setAttributes({ opening_We_open: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Wednesday (closed)',
									placeholder:'18:00',
									value: props.attributes.opening_We_close,
									onChange: function( val ) {
										props.setAttributes({ opening_We_close: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Thursday (open)',
									placeholder:'9:00',
									value: props.attributes.opening_Th_open,
									onChange: function( val ) {
										props.setAttributes({ opening_Th_open: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Thursday (closed)',
									placeholder:'18:00',
									value: props.attributes.opening_Th_close,
									onChange: function( val ) {
										props.setAttributes({ opening_Th_close: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Friday (open)',
									placeholder:'9:00',
									value: props.attributes.opening_Fr_open,
									onChange: function( val ) {
										props.setAttributes({ opening_Fr_open: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Friday (closed)',
									placeholder:'18:00',
									value: props.attributes.opening_Fr_close,
									onChange: function( val ) {
										props.setAttributes({ opening_Fr_close: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Saturday (open)',
									placeholder:'9:00',
									value: props.attributes.opening_Sa_open,
									onChange: function( val ) {
										props.setAttributes({ opening_Sa_open: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Saturday (closed)',
									placeholder:'18:00',
									value: props.attributes.opening_Sa_close,
									onChange: function( val ) {
										props.setAttributes({ opening_Sa_close: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Sunday (open)',
									placeholder:'9:00',
									value: props.attributes.opening_Su_open,
									onChange: function( val ) {
										props.setAttributes({ opening_Su_open: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Sunday (closed)',
									placeholder:'18:00',
									value: props.attributes.opening_Su_close,
									onChange: function( val ) {
										props.setAttributes({ opening_Su_close: val })
									}
								}
							),
						),
						createElement(
							PanelBody,
							{
								title:'Restaurant info',
								initialOpen:false,
							},
							createElement(
								TextControl, {
									label: 'URL to menu page',
									value: props.attributes.company_menu,
									onChange: function( val ) {
										props.setAttributes({ company_menu: val })
									}
								}
							),
							createElement(
								TextControl, {
									label: 'Accepts reservations',
									value: props.attributes.company_reservations,
									help:__('Enter an URL or type 1 if no URL is available','pep'),
									onChange: function( val ) {
										props.setAttributes({ company_reservations: val })
									}
								}
							),
						),
					),
				];
		
        return [
			controls,
            createElement('div', { className: props.className,style:simpleStyle},
                   createElement(
						RichText,
						{
							tagName:'h3',
							value: company_name,
							keepPlaceholderOnFocus:true,
							placeholder:'Company name',
							onChange: function( val ) {
								props.setAttributes({ company_name: val })
							},
							multiline:false,
							focus:focus,
							onFocus:props.setFocus,
						},
						//__( 'Company Name','pep' )
					),
					createElement(
						RichText,
						{
							tagName:'em',
							value: subline,
							placeholder:'Subline',
							keepPlaceholderOnFocus:true,
							onChange: function( val ) {
								props.setAttributes({ subline: val })
							},
							multiline:false,
							focus:focus,
							onFocus:props.setFocus,
						},
						//__( 'Subline','pep' )
					),
					createElement(
						RichText,
						{
							tagName:'span',
							value: street,
							placeholder:'Street + number',
							keepPlaceholderOnFocus:true,
							onChange: function( val ) {
								props.setAttributes({ street: val })
							},
							multiline:false,
							focus:focus,
							onFocus:props.setFocus,
						},
						//__( 'Street','pep' )
					),
					createElement(
						RichText,
						{
							tagName:'span',
							value: postcode,
							placeholder:'0000 AA',
							keepPlaceholderOnFocus:true,
							onChange: function( val ) {
								props.setAttributes({ postcode: val })
							},
							multiline:false,
							focus:focus,
							onFocus:props.setFocus,
						},
						//__( 'Postcode','pep' )
					),
					createElement(
						RichText,
						{
							tagName:'span',
							value: city,
							placeholder:'City',
							keepPlaceholderOnFocus:true,
							onChange: function( val ) {
								props.setAttributes({ city: val })
							},
							multiline:false,
							focus:focus,
							onFocus:props.setFocus,
						},
						//__( 'City','pep' )
					),
					createElement(
						RichText,
						{
							tagName:'span',
							value: phone,
							placeholder:'Phone number',
							keepPlaceholderOnFocus:true,
							onChange: function( val ) {
								props.setAttributes({ phone: val })
							},
							multiline:false,
							focus:focus,
							onFocus:props.setFocus,
						},
						//__( 'Phone number','pep' )
					),
					createElement(
						RichText,
						{
							tagName:'span',
							value: email,
							placeholder:'voorbeeld@pepbc.nl',
							keepPlaceholderOnFocus:true,
							onChange: function( val ) {
								props.setAttributes({ email: val })
							},
							multiline:false,
							focus:focus,
							onFocus:props.setFocus,
						},
						//__( 'Email address','pep' )
					),
                )

        ];
    },

    //Save
	// Defines the saved block.
	save: function() {
		return null;
	},
			
	}
	);
	
	/**
	 * Register block
	 *
	 * @param  {string}   name     Block name.
	 * @param  {Object}   settings Block settings.
	 * @return {?WPBlock}          Block itself, if registered successfully,
	 *                             otherwise "undefined".
	 */
	registerBlockType(
		'pep/slickslider', // Block name. Must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
		{
			title: __( 'Slick slider' ), // Block title. __() function allows for internationalization.
			description: __( 'Create a slider from inner elements.','pep' ), // Block description.
			icon: 'images-alt', // Block icon from Dashicons. https://developer.wordpress.org/resource/dashicons/.
			category: 'layout', // Block category. Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
			supports: {
				align:['full','wide']
			},
			attributes: {
				content: {
		            type: 'array',
		            selector: 'div',
				},
				align: {
					type: 'string',
					default: ''
				},
				selectElement: {
					type: 'string',
					default: ''
				},
				load_slides: {
					type: 'integer',
					default: '1'
				},
				slides_to_scroll: {
					type: 'integer',
					default: '1'
				},
				show_arrows: {
					type: 'bool',
					default: false
				},				
				show_controls: {
					type: 'bool',
					default: false
				},
				arialabel: {
					type: 'string',
					default: ''
				},
				infinite: {
					type: 'bool',
					default: false
				},
				appendArrows: {
					type: 'string',
					default: ''
				},
				prevArrow: {
					type: 'string',
					default: 'Vorige'
				},
				nextArrow: {
					type: 'string',
					default: 'Volgende'
				},
				appendDots: {
					type: 'string',
					default: ''
				},
				customPaging: {
					type: 'string',
					default: ''
				},
				auto_play: {
					type: 'bool',
					default: false
				},
				speed: {
					type: 'integer',
					default:500,
				},
				auto_play_speed: {
					type: 'integer',
					default:5000,
				},
				slickStyling: {
					type: 'string',
					default:'default',
				},
			},
			//Edit
			edit: function( props ) {
				const controls = [
					createElement(
						InspectorControls,
						{},
						createElement(
							PanelBody,
							{
							}
						),
						createElement(
							TextControl, {
								label: 'Slider element',
								value: props.attributes.selectElement,
								type:'text',
								help:'Define DOM-element to slide',
								onChange: function( val ) {
									props.setAttributes({ selectElement: val })
								}
							}
						),
						
						createElement(
							RangeControl, {
								label: 'Columns',
								value: props.attributes.load_slides,
								initialPosition: 1,
								min: 1,
								max: 10,
								onChange: function( val ) {
									props.setAttributes({ load_slides: val })
								}
							}
						),
						createElement(
							RangeControl, {
								label: 'Slides to scroll',
								value: props.attributes.slides_to_scroll,
								initialPosition: 1,
								min: 1,
								max: 10,
								onChange: function( val ) {
									props.setAttributes({ slides_to_scroll: val })
								}
							}
						),
						createElement(
							ToggleControl, {
								label: 'Show prev/next arrows',
								help: '',
								value:props.attributes.show_arrows,
								checked: props.attributes.show_arrows,
								onChange: function( val ) {
									props.setAttributes({ show_arrows: val })
								}
							}
						),
						createElement(
							TextControl, {
								label: 'Append arrows',
								value: props.attributes.appendArrows,
								type:'text',
								help:'Append arrows to element, enter CSS-class name of element',
								onChange: function( val ) {
									props.setAttributes({ appendArrows: val })
								}
							}
						),
						createElement(
							TextControl, {
								label: 'Text previous arrow',
								value: props.attributes.prevArrow,
								type:'text',
								onChange: function( val ) {
									props.setAttributes({ prevArrow: val })
								}
							}
						),
						createElement(
							TextControl, {
								label: 'Text next arrow',
								value: props.attributes.nextArrow,
								type:'text',
								onChange: function( val ) {
									props.setAttributes({ nextArrow: val })
								}
							}
						),
						createElement(
							TextControl, {
								label: 'Aria-label for tablist',
								value: props.attributes.arialabel,
								type:'text',
								onChange: function( val ) {
									props.setAttributes({ arialabel: val })
								}
							}
						),
						createElement(
							ToggleControl, {
								label: 'Show controls (bullets)',
								help: '',
								value: props.attributes.show_arrows,
								checked: props.attributes.show_controls,
								onChange: function( val ) {
									props.setAttributes({ show_controls: val })
								}
							}
						),
						createElement(
							TextControl, {
								label: 'Append bullets',
								value: props.attributes.appendDots,
								type:'text',
								help:'Append bullets to element, enter CSS-class name of element',
								onChange: function( val ) {
									props.setAttributes({ appendDots: val })
								}
							}
						),
						createElement(
							TextControl, {
								label: 'Custom Paging',
								value: props.attributes.customPaging,
								type:'text',
								help:'Enter a comma-separated string for each bullet-title.',
								onChange: function( val ) {
									props.setAttributes({ customPaging: val })
								}
							}
						),
						createElement(
							ToggleControl, {
								label: 'Infinite loop',
								help: '',
								value: props.attributes.infinite,
								checked: props.attributes.infinite,
								onChange: function( val ) {
									props.setAttributes({ infinite: val })
								}
							}
						),
						createElement(
							ToggleControl, {
								label: 'Auto play',
								help: '',
								value:props.attributes.auto_play,
								checked: props.attributes.auto_play,
								onChange: function( val ) {
									props.setAttributes({ auto_play: val })
								}
							}
						),
						createElement(
								RangeControl, {
									label: 'Auto Play Speed',
									value: props.attributes.auto_play_speed,
									initialPosition: 5000,
									min: 0,
									max: 10000,
									step:100,
									onChange: function( val ) {
										props.setAttributes({ auto_play_speed: val })
									}
								}
						),
						createElement(
								RangeControl, {
									label: 'Speed',
									value: props.attributes.speed,
									initialPosition: 5000,
									min: 0,
									max: 10000,
									step:100,
									onChange: function( val ) {
										props.setAttributes({ speed: val })
									}
								}
						),
						createElement(
							SelectControl, {
								label: 'Type slider',
								value: props.attributes.slickStyling,
								options:[
									{label: 'Default', value:'default'},
									{label: 'Tabs', value:'tabs'},
								],
								onChange: function( val ) {
									props.setAttributes({ slickStyling: val })
								}
							}
						),
					),
				];					

				return [
					controls,
					createElement('div', { className: props.className,style:blockStyle},
                   createElement(
						InnerBlocks,
						{
							template:SLICKSLIDER,
							//templateLock:'false',
							allowedBlocks: SLICKSLIDER_ALLOWED,
						},						
					),
                ),
				];
    },

    save: function( props ) {
		var content=InnerBlocks.Content;
		return (
			createElement('div', {className: props.className},
				createElement( content, null )
			)
		);
	}
    
			
		}
	);
	
})();