const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
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
		'reviews'
	],
	supports: {
		html: false,
	},
	attributes: {},
	edit: () => <ServerSideRender block="google-places-reviews/reviews" />,
	save: () => null,
} );
