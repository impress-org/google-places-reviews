const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, ToggleControl, TextControl, SelectControl } = wp.components;
const ServerSideRender = wp.serverSideRender;

import icon from './icon';

registerBlockType( 'google-places-reviews/reviews', {
	title: __( 'Google Places Reviews', 'google-places-reviews' ),
	description: __( 'Easily display Google Reviews on your WordPress website using a powerful and intuitive widget.', 'google-places-reviews' ),
	category: 'widgets',
	icon,
	keywords: [
		'google',
		'places',
		'reviews',
	],
	supports: {
		html: false,
	},
	attributes: {
		title: {
			type: 'string',
		},
		location: {
			type: 'string',
		},
		reference: {
			type: 'string',
		},
		place_id: {
			type: 'string',
		},
		place_type: {
			type: 'string',
			default: 'all',
		},
		cache: {
			type: 'string',
			default: '1 Day',
		},
		disable_title_output: {
			type: 'boolean',
			default: false,
		},
		widget_style: {
			type: 'string',
			default: 'Minimal Light',
		},
		review_filter: {
			type: 'string',
		},
		review_limit: {
			type: 'number',
			default: 3,
		},
		review_characters: {
			type: 'string',
		},
		hide_header: {
			type: 'boolean',
			default: false,
		},
		hide_out_of_rating: {
			type: 'boolean',
			default: false,
		},
		hide_google_image: {
			type: 'boolean',
			default: false,
		},
		target_blank: {
			type: 'boolean',
			default: true,
		},
		no_follow: {
			type: 'boolean',
			default: true,
		},
	},
	edit: ( { attributes, setAttributes } ) => (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Google Places Reviews', 'google-places-reviews' ) } initialOpen={ true }>
					<TextControl
						name="title"
						label={ __( 'Widget Title', 'google-places-reviews' ) }
						value={ attributes.title }
						onChange={ title => setAttributes( { title } ) }
					/>

					<TextControl
						name="place_id"
						label={ __( 'Place ID', 'google-places-reviews' ) }
						value={ attributes.place_id }
						onChange={ place_id => setAttributes( { place_id } ) }
					/>

					<SelectControl
						label={ __( 'Place Type', 'google-places-reviews' ) }
						value={ attributes.place_type }
						options={ [
							{ label: __( 'All', 'google-places-reviews' ), value: 'all' },
							{ label: __( 'Addresses', 'google-places-reviews' ), value: 'address' },
							{ label: __( 'Establishments', 'google-places-reviews' ), value: 'establishment' },
							{ label: __( 'Regions', 'google-places-reviews' ), value: '(regions)' },
						] }
						onChange={ place_type => setAttributes( { place_type } ) }
						help={ __( 'Specify how you would like to lookup your Google Places. Address instructs the Place Autocomplete service to return only geocoding results with a precise address. Establishment instructs the Place Autocomplete service to return only business results. The Regions type collection instructs the Places service to return any result matching the following types: locality, sublocality, postal_code, country, administrative_area_level_1, administrative_area_level_2.', 'google-places-reviews' ) }
					/>
				</PanelBody>

				<PanelBody title={ __( 'Review Options', 'google-places-reviews' ) } initialOpen={ false }>

					<p className="pro-field">
						<TextControl
							disabled
							label={ __( 'Minimum Review Rating', 'google-places-reviews' ) }
							value="No filter"
							help={ __( 'PRO FEATURE: Filter bad reviews to prevent them from displaying. Please note that the Google Places API only allows for up to 5 total reviews displayed per location. This option may limit the total number further.', 'google-places-reviews' ) }
						/>
					</p>

					<SelectControl
						label={ __( 'Limit Number of Reviews', 'google-places-reviews' ) }
						value={ attributes.review_limit }
						options={ [
							{ label: 1, value: 1 },
							{ label: 2, value: 2 },
							{ label: 3, value: 3 },
						] }
						onChange={ review_limit => setAttributes( { review_limit } ) }
						help={ __( 'Limit the number of reviews displayed for this location to a set number.', 'google-places-reviews' ) }
					/>

				</PanelBody>

				<PanelBody title={ __( 'Display Options', 'google-places-reviews' ) } initialOpen={ false }>
					<SelectControl
						label={ __( 'Widget Theme', 'google-places-reviews' ) }
						value={ attributes.widget_style }
						options={ [
							{ label: __( 'Bare Bones', 'google-places-reviews' ), value: 'Bare Bones' },
							{ label: __( 'Minimal Light', 'google-places-reviews' ), value: 'Minimal Light' },
							{ label: __( 'Minimal Dark', 'google-places-reviews' ), value: 'Minimal Dark' },
							{ label: __( 'Shadow Light', 'google-places-reviews' ), value: 'Shadow Light' },
							{ label: __( 'Shadow Dark', 'google-places-reviews' ), value: 'Shadow Dark' },
						] }
						onChange={ widget_style => setAttributes( { widget_style } ) }
						help={ __( 'Choose from a set of predefined widget styles. Want to style your own? Set it to \'Bare Bones\' for easy CSS styling.', 'google-places-reviews' ) }
					/>

					<ToggleControl
						label={ __( 'Hide Business Information', 'google-places-reviews' ) }
						checked={ attributes.hide_header }
						onChange={ hide_header => setAttributes( { hide_header } ) }
						help={ __( 'Disable the main business information profile image, name, overall rating. Useful for displaying only ratings in the widget.', 'google-places-reviews' ) }
					/>

					<ToggleControl
						label={ __( 'Hide "x out of 5 stars" text', 'google-places-reviews' ) }
						checked={ attributes.hide_out_of_rating }
						onChange={ hide_out_of_rating => setAttributes( { hide_out_of_rating } ) }
						help={ __( 'Hide the text the appears after the star image displaying \'x out of 5 stars\'. The text will still be output because it is important for SEO but it will be hidden with CSS.', 'google-places-reviews' ) }
					/>

					<ToggleControl
						label={ __( 'Hide Google logo', 'google-places-reviews' ) }
						checked={ attributes.hide_google_image }
						onChange={ hide_google_image => setAttributes( { hide_google_image } ) }
						help={ __( 'Prevent the Google logo from displaying in the reviews widget.', 'google-places-reviews' ) }
					/>

				</PanelBody>

				<PanelBody title={ __( 'Advanced Options', 'google-places-reviews' ) } initialOpen={ false }>
					<SelectControl
						label={ __( 'Cache Data', 'google-places-reviews' ) }
						value={ attributes.cache }
						options={ [
							{ label: __( 'None', 'google-places-reviews' ), value: 'None' },
							{ label: __( '1 Hour', 'google-places-reviews' ), value: '1 Hour' },
							{ label: __( '3 Hour', 'google-places-reviews' ), value: '3 Hour' },
							{ label: __( '6 Hour', 'google-places-reviews' ), value: '6 Hour' },
							{ label: __( '12 Hour', 'google-places-reviews' ), value: '12 Hour' },
							{ label: __( '1 Day', 'google-places-reviews' ), value: '1 Day' },
							{ label: __( '2 Days', 'google-places-reviews' ), value: '2 Days' },
							{ label: __( '1 Week', 'google-places-reviews' ), value: '1 Week' },
						] }
						onChange={ cache => setAttributes( { cache } ) }
						help={ __( 'Caching data will save Google Place data to your database in order to speed up response times and conserve API requests. The suggested settings is 1 Day.', 'google-places-reviews' ) }
					/>

					<p>
						<button className="button button-primary">
							{ __( 'Clear cache', 'google-places-reviews' ) }
						</button>
					</p>

					<br />

					<ToggleControl
						label={ __( 'Disable Title Output', 'google-places-reviews' ) }
						checked={ attributes.disable_title_output }
						onChange={ disable_title_output => setAttributes( { disable_title_output } ) }
						help={ __( 'The title output is content within the \'Widget Title\' field above. Disabling the title output may be useful for some themes.', 'google-places-reviews' ) }
					/>

					<ToggleControl
						label={ __( 'Open Links in New Window', 'google-places-reviews' ) }
						checked={ attributes.target_blank }
						onChange={ target_blank => setAttributes( { target_blank } ) }
						help={ __( 'This option will add target=\'_blank\' to the widget\'s links. This is useful to keep users on your website.', 'google-places-reviews' ) }
					/>

					<ToggleControl
						label={ __( 'No Follow Links', 'google-places-reviews' ) }
						checked={ attributes.no_follow }
						onChange={ no_follow => setAttributes( { no_follow } ) }
						help={ __( 'This option will add rel=\'nofollow\' to the widget\'s outgoing links. This option may be useful for SEO.', 'google-places-reviews' ) }
					/>

				</PanelBody>
			</InspectorControls>

			<ServerSideRender block="google-places-reviews/reviews" attributes={ attributes }/>
		</Fragment>
	),
	save: () => null,
} );
