/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import edit from './edit';
import save from './save';

/**
 * Block icon
 */
const icon = (
	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z" fill="currentColor"/>
	</svg>
);

/**
 * Register the block
 */
registerBlockType('interactive-related-posts/related-posts', {
	title: __('Interactive Related Posts', 'interactive-related-posts'),
	description: __('Display related posts with customizable layouts', 'interactive-related-posts'),
	category: 'widgets',
	icon: icon,
	keywords: [
		__('related', 'interactive-related-posts'),
		__('posts', 'interactive-related-posts'),
		__('interactive', 'interactive-related-posts'),
	],
	supports: {
		html: false,
		align: ['wide', 'full'],
	},
	attributes: {
		selectedPosts: {
			type: 'array',
			default: [],
		},
		posts: {
			type: 'number',
			default: 6,
		},
		columns: {
			type: 'number',
			default: 3,
		},
		layout: {
			type: 'string',
			default: 'grid',
		},
		showExcerpt: {
			type: 'boolean',
			default: true,
		},
		showDate: {
			type: 'boolean',
			default: true,
		},
		showAuthor: {
			type: 'boolean',
			default: false,
		},
		showCategory: {
			type: 'boolean',
			default: true,
		},
		category: {
			type: 'string',
			default: '',
		},
		tag: {
			type: 'string',
			default: '',
		},
		orderby: {
			type: 'string',
			default: 'date',
		},
		order: {
			type: 'string',
			default: 'DESC',
		},
	},
	edit,
	save,
});
