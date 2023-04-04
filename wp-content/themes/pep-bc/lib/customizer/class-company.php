<?php
/*
 * Customizer functionality - Company
 */
namespace PEP\Customizer;

class Company {

	 public function __construct()
     {
        add_action('customize_register', array($this, 'add_customizer_company'));
     }

	 public function get_company_info() {
		$food=array(
			'Bakery'=>__('Food Establishment','pep').' - '.__('Bakery','pep'),
			'BarOrPub'=>__('Food Establishment','pep').' - '.__('Bar or Pub','pep'),
			'Brewery'=>__('Food Establishment','pep').' - '.__('Brewery','pep'),
			'CafeOrCoffeeShop'=>__('Food Establishment','pep').' - '.__('Cafe or Coffee Shop','pep'),
			'FastFoodRestaurant'=>__('Food Establishment','pep').' - '.__('FastFoodRestaurant','pep'),
			'IceCreamShop'=>__('Food Establishment','pep').' - '.__('Ice Cream Shop','pep'),
			'Restaurant'=>__('Food Establishment','pep').' - '.__('Restaurant','pep'),
			'Winery'=>__('Food Establishment','pep').' - '.__('Winery','pep'),
		);

		$schema_org=array(
			'LocalBusiness'=>__('Local Business (default)','pep'),
			'ChildCare'=>__('ChildCare','pep'),
			'Corporation'=>__('Corporation','pep'),
			'Dentist'=>__('Dentist','pep'),
			'EducationalOrganization'=>__('Educational Organization','pep'),
			'GovernmentOrganization'=>__('Government Organization','pep'),
			'HealthAndBeautyBusiness'=>__('Health and Beauty Business','pep'),
			'BeautySalon'=>__('Health and Beauty Business','pep') .' - '.__('Beauty Salon','pep'),
			'DaySpa'=>__('Health and Beauty Business','pep') .' - '.__('Day Spa','pep'),
			'HairSalon'=>__('Health and Beauty Business','pep') .' - '.__('Hair Salon','pep'),
			'HealthClub'=>__('Health and Beauty Business','pep') .' - '.__('Health Club','pep'),
			'NailSalon'=>__('Health and Beauty Business','pep') .' - '.__('Nail Salon','pep'),
			'MedicalOrganization'=>__('Medical Organization','pep'),
			'NGO'=>__('Non-governmental Organisation','pep'),
			'PerformingGroup'=>__('Performance Group','pep'),
			'Project'=>__('Project','pep'),
			'RealEstateAgent'=>__('Real Estate Agent','pep'),
			'SportOrganization'=>__('Sport Organization','pep'),
			'SportsActivityLocation'=>__('Sports Activity Location','pep'),
			'Store'=>__('Store','pep'),
			'BikeStore'=>__('Store','pep').' - '. __('Bike Store','pep'),
			'BookStore'=>__('Store','pep').' - '. __('Book Store','pep'),
			'ClothingStore'=>__('Store','pep').' - '. __('Clothing Store','pep'),
			'ComputerStore'=>__('Store','pep').' - '. __('Computer Store','pep'),
			'ElectronicsStore'=>__('Store','pep').' - '. __('Electronics Store','pep'),
			'Florist'=>__('Store','pep').' - '. __('Florist','pep'),
			'GardenStore'=>__('Store','pep').' - '. __('Garden Store','pep'),
			'HobbyShop'=>__('Store','pep').' - '. __('Hobby Shop','pep'),
			'JewelryStore'=>__('Store','pep').' - '. __('Jewelry Store','pep'),
			'MobilePhoneStore'=>__('Store','pep').' - '. __('Mobile Phone Store','pep'),
			'OfficeEquipmentStore'=>__('Store','pep').' - '. __('Office Equipment Store','pep'),
			'PetStore'=>__('Store','pep').' - '. __('Pet Store','pep'),
			'ShoeStore'=>__('Store','pep').' - '. __('Shoe Store','pep'),
			'ToyStore'=>__('Store','pep').' - '. __('Toy Store','pep'),
			'TravelAgency'=>__('Travel Agency','pep'),
		);

		$schema_org=array_merge($schema_org,$food);
		asort($schema_org);

		 $info=array(
			'company_general'=>array(
				'title'=>__('General','pep'),
				'description'=>'',
				'priority'=>10,
				'controls'=>array(
					'schema_org'=>array(
						'label'=>__('Company type','pep'),
						'type'=>'select',
						'default'=>'LocalBusiness',
						'choices'=>$schema_org,
					),
					'company_name'=>array(
						'label'=>__('Company name','pep'),
						'type'=>'text',
						'default'=>get_bloginfo('name'),
					),
					'company_subline'=>array(
						'label'=>__('Subline','pep'),
						'type'=>'text',
						'description'=>__('Extra information, for example: no visiting address','pep'),
						'default'=>'',
					),
					'company_email'=>array(
						'label'=>__('Email address','pep'),
						'type'=>'text',
						'default'=>get_bloginfo('admin_email'),
					),
					'company_address'=>array(
						'label'=>__('Address','pep'),
						'type'=>'text',
						'default'=>'',
					),
					'company_postal'=>array(
						'label'=>__('Postal code','pep'),
						'type'=>'text',
						'default'=>'',
					),
					'company_city'=>array(
						'label'=>__('City','pep'),
						'type'=>'text',
						'default'=>'',
					),
					'company_country'=>array(
						'label'=>__('Country','pep'),
						'type'=>'text',
						'default'=>'The Netherlands',
					),
					'company_phone'=>array(
						'label'=>__('Phone','pep'),
						'type'=>'text',
						'default'=>'',
					),
					'company_fax'=>array(
						'label'=>__('Fax','pep'),
						'type'=>'text',
						'default'=>'',
					),
				),
			),
			'company_socials'=>array(
				'title'=>__('Social media','pep'),
				'description'=>'',
				'priority'=>20,
				'controls'=>array(
					'social_placeholder'=>array(
						'label'=>__('Social media settings','pep'),
						'description'=>'<a href="/wp-admin/admin.php?page=wpseo_social">'.__('Use Yoast SEO settings to enter social media settings','pep').'</a>',
						'type'=>'hidden',
						'default'=>'',
					),
				),
			),
			'company_hours'=>array(
				'title'=>__('Openinghours','pep'),
				'description'=>'',
				'priority'=>30,
				'controls'=>array(
					'opening_Mo_open'=>array(
						'label'=>__('Monday') .' - '.__('Open','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_Mo_close'=>array(
						'label'=>__('Monday') .' - '.__('Closed','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_Tu_open'=>array(
						'label'=>__('Tuesday') .' - '.__('Open','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_Tu_close'=>array(
						'label'=>__('Tuesday') .' - '.__('Closed','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_We_open'=>array(
						'label'=>__('Wednesday') .' - '.__('Open','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_We_close'=>array(
						'label'=>__('Wednesday') .' - '.__('Closed','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_Th_open'=>array(
						'label'=>__('Thursday') .' - '.__('Open','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_Th_close'=>array(
						'label'=>__('Thursday') .' - '.__('Closed','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_Fr_open'=>array(
						'label'=>__('Friday') .' - '.__('Open','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_Fr_close'=>array(
						'label'=>__('Friday') .' - '.__('Closed','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_Sa_open'=>array(
						'label'=>__('Saturday') .' - '.__('Open','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_Sa_close'=>array(
						'label'=>__('Saturday') .' - '.__('Closed','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_Su_open'=>array(
						'label'=>__('Sunday') .' - '.__('Open','pep'),
						'type'=>'time',
						'default'=>'',
					),
					'opening_Su_close'=>array(
						'label'=>__('Sunday') .' - '.__('Closed','pep'),
						'type'=>'time',
						'default'=>'',
					),
				),
			),

			'company_other'=>array(
				'title'=>__('Other info','pep'),
				'description'=>'',
				'priority'=>80,
				'controls'=>array(
					'company_kvk'=>array(
						'label'=>__('Chamber of Commerce number','pep'),
						'type'=>'text',
						'default'=>'',
					),
					'company_vat'=>array(
						'label'=>__('VAT number','pep'),
						'type'=>'text',
						'default'=>'',
					),
					'company_pricerange'=>array(
						'label'=>__('Price range','pep'),
						'type'=>'select',
						'default'=>'',
						'choices'=>array(
							'$'=>__('Low','pep'),
							'$$'=>__('Medium','pep'),
							'$$$'=>__('High','pep'),
						)
					),
					'company_descr'=>array(
						'label'=>__('Short description','pep'),
						'type'=>'textarea',
						'default'=>'',
					),

				),
			),
			'company_google'=>array(
				'title'=>__('Google Analytics / GTM','pep'),
				'description'=>__('Use UA-code or GTM-code only. Not both','pep'),
				'priority'=>75,
				'controls'=>array(
					'company_gtag'=>array(
						'label'=>__('UA-code','pep'),
						'type'=>'text',
						'default'=>'',
						'placeholder'=>'UA-XXXXXXXX-X',
						'description'=>__('Enter Google Analytics like: UA-XXXXXXXX-X','pep'),
					),
					'company_gtm'=>array(
						'label'=>__('GTM-code','pep'),
						'type'=>'text',
						'default'=>'',
						'placeholder'=>'GTM-XXXX',
						'description'=>__('Enter Google Tag Manager code like: GTM-XXXX','pep'),
					),
					
				),
			),
		 );
		 $schema= get_theme_mod('schema_org');
		 if(!empty($schema) && array_key_exists($schema,$food)) {
			$info['company_restaurant']=array(
				'title'=>__('Restaurant info','pep'),
				'description'=>'',
				'priority'=>40,
				'controls'=>array(
					'company_menu'=>array(
						'label'=>__('URL to Menu (restaurants only)','pep'),
						'type'=>'text',
						'default'=>'',
					),
					'company_reservations'=>array(
						'label'=>__('Accepts reservations','pep'),
						'description'=>__('Enter an URL or type 1 if no URL is available','pep'),
						'type'=>'text',
						'default'=>'',
					),
				),
			 );
		 }

		 return $info;
	 }


	 public function add_customizer_company($wp_customize) {
		$wp_customize->add_panel('company',array(
			'title'=>__('Company info','pep'),
			'priority'=>70,
		));

		$company=$this->get_company_info();

		$i=0;
		foreach($company as $section => $options) {
			$wp_customize->add_section($section,array(
				'title'=>$options['title'],
				'priority'=>$options['priority'],
				'panel'=>'company',
				'description'=>$options['description'],
			));

			foreach($options['controls'] as $id => $control) {

				$description='';
				if(isset($control['description']))	$description=$control['description'];

				$wp_customize->add_setting(
					$id,
					array(
						'default'           => $control['default'],
					)
				);

				$options=array(
					'type' => $control['type'],
					'section' => $section, // Add a default or your own section
					'label' => $control['label'],
					'description' => $description,
				);
				if(isset($control['placeholder'])) {
					$options['input_attrs']=array(
						'placeholder'=> esc_attr($control['placeholder'])
					);
				}

				if(isset($control['choices'])) {
					$options['choices']=$control['choices'];
				}

				$wp_customize->add_control( $id, $options );
			}

			$i++;

		}
	 }

}