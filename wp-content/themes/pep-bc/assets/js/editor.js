wp.domReady( () => {
	wp.blocks.unregisterBlockStyle( 'core/button', 'default' );
	wp.blocks.unregisterBlockStyle( 'core/button', 'squared' );
	wp.blocks.unregisterBlockStyle( 'core/button', 'outline' );
	wp.blocks.registerBlockStyle( 
		'core/button', [ 
		{
			name: 'squared',
			label: 'Squared',
			isDefault: true,
		},
		{
			name: 'outline',
			label: 'Outline',
			isDefault: false,
		},
		{
			name: 'rounded',
			label: 'Rounded',
			isDefault: false,
		}
	]);

	wp.blocks.registerBlockStyle( 'core/heading', [ 
		{
			name: 'default',
			label: 'Default',
			isDefault: true,
		},
		{
			name: 'uppercase',
			label: 'Uppercase',
		},
		{
			name: 'alternative',
			label: 'Alternative',
		}		
	]);
	
	wp.blocks.registerBlockStyle( 'core/list', [ 
		{
			name: 'default',
			label: 'Default',
			isDefault: true,
		},
		{
			name: 'checkmark',
			label: 'Checkmarks',
		}
	]);
} );