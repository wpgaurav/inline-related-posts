/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	SelectControl,
	ToggleControl,
	Button,
	Placeholder,
	Spinner,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useState, useEffect } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Edit component
 */
export default function Edit({ attributes, setAttributes, clientId }) {
	const {
		selectedPosts,
		posts,
		columns,
		layout,
		showExcerpt,
		showDate,
		showAuthor,
		showCategory,
		category,
		tag,
		orderby,
		order,
	} = attributes;

	const [searchTerm, setSearchTerm] = useState('');
	const [isSearching, setIsSearching] = useState(false);

	// Get available posts for selection
	const { postsList, isLoading } = useSelect(
		(select) => {
			const { getEntityRecords } = select('core');
			const query = {
				per_page: 20,
				orderby: 'date',
				order: 'desc',
				status: 'publish',
			};

			if (searchTerm) {
				query.search = searchTerm;
			}

			return {
				postsList: getEntityRecords('postType', 'post', query) || [],
				isLoading: select('core/data').isResolving('core', 'getEntityRecords', [
					'postType',
					'post',
					query,
				]),
			};
		},
		[searchTerm]
	);

	// Layout options
	const layoutOptions = [
		{ label: __('Grid', 'interactive-related-posts'), value: 'grid' },
		{ label: __('Card', 'interactive-related-posts'), value: 'card' },
		{ label: __('List', 'interactive-related-posts'), value: 'list' },
		{ label: __('Thumbnail', 'interactive-related-posts'), value: 'thumbnail' },
		{ label: __('Link Only', 'interactive-related-posts'), value: 'link-only' },
		{ label: __('Minimal', 'interactive-related-posts'), value: 'minimal' },
	];

	// Order by options
	const orderbyOptions = [
		{ label: __('Date', 'interactive-related-posts'), value: 'date' },
		{ label: __('Title', 'interactive-related-posts'), value: 'title' },
		{ label: __('Random', 'interactive-related-posts'), value: 'rand' },
		{ label: __('Modified', 'interactive-related-posts'), value: 'modified' },
	];

	// Order options
	const orderOptions = [
		{ label: __('Descending', 'interactive-related-posts'), value: 'DESC' },
		{ label: __('Ascending', 'interactive-related-posts'), value: 'ASC' },
	];

	// Add post to selection
	const addPost = (post) => {
		const isAlreadySelected = selectedPosts.some((p) => p.id === post.id);
		if (!isAlreadySelected) {
			setAttributes({
				selectedPosts: [
					...selectedPosts,
					{
						id: post.id,
						title: post.title.rendered,
					},
				],
			});
		}
	};

	// Remove post from selection
	const removePost = (postId) => {
		setAttributes({
			selectedPosts: selectedPosts.filter((p) => p.id !== postId),
		});
	};

	const blockProps = useBlockProps({
		className: 'irp-block-editor',
	});

	return (
		<div {...blockProps}>
			<InspectorControls>
				{/* Post Selection Panel */}
				<PanelBody title={__('Post Selection', 'interactive-related-posts')} initialOpen={true}>
					<div className="irp-post-selector">
						<p>
							{__('Manually select posts or leave empty to show recent posts automatically.', 'interactive-related-posts')}
						</p>

						{/* Selected Posts */}
						{selectedPosts.length > 0 && (
							<div className="irp-selected-posts">
								<h4>{__('Selected Posts:', 'interactive-related-posts')}</h4>
								<ul>
									{selectedPosts.map((post) => (
										<li key={post.id}>
											<span>{post.title}</span>
											<Button
												isSmall
												isDestructive
												onClick={() => removePost(post.id)}
											>
												{__('Remove', 'interactive-related-posts')}
											</Button>
										</li>
									))}
								</ul>
								<Button
									isSecondary
									onClick={() => setAttributes({ selectedPosts: [] })}
								>
									{__('Clear All', 'interactive-related-posts')}
								</Button>
							</div>
						)}

						{/* Search Posts */}
						<div className="irp-post-search">
							<h4>{__('Add Posts:', 'interactive-related-posts')}</h4>
							<input
								type="text"
								placeholder={__('Search posts...', 'interactive-related-posts')}
								value={searchTerm}
								onChange={(e) => setSearchTerm(e.target.value)}
								className="components-text-control__input"
							/>

							{isLoading && <Spinner />}

							{!isLoading && postsList.length > 0 && (
								<ul className="irp-posts-list">
									{postsList.map((post) => {
										const isSelected = selectedPosts.some((p) => p.id === post.id);
										return (
											<li key={post.id}>
												<span>{post.title.rendered}</span>
												<Button
													isSmall
													isPrimary={!isSelected}
													disabled={isSelected}
													onClick={() => addPost(post)}
												>
													{isSelected
														? __('Selected', 'interactive-related-posts')
														: __('Add', 'interactive-related-posts')}
												</Button>
											</li>
										);
									})}
								</ul>
							)}
						</div>
					</div>

					{selectedPosts.length === 0 && (
						<>
							<RangeControl
								label={__('Number of Posts', 'interactive-related-posts')}
								value={posts}
								onChange={(value) => setAttributes({ posts: value })}
								min={1}
								max={20}
							/>
						</>
					)}
				</PanelBody>

				{/* Layout Settings Panel */}
				<PanelBody title={__('Layout Settings', 'interactive-related-posts')}>
					<SelectControl
						label={__('Layout Style', 'interactive-related-posts')}
						value={layout}
						options={layoutOptions}
						onChange={(value) => setAttributes({ layout: value })}
					/>

					<RangeControl
						label={__('Columns', 'interactive-related-posts')}
						value={columns}
						onChange={(value) => setAttributes({ columns: value })}
						min={1}
						max={6}
					/>
				</PanelBody>

				{/* Display Options Panel */}
				<PanelBody title={__('Display Options', 'interactive-related-posts')} initialOpen={false}>
					<ToggleControl
						label={__('Show Excerpt', 'interactive-related-posts')}
						checked={showExcerpt}
						onChange={(value) => setAttributes({ showExcerpt: value })}
					/>

					<ToggleControl
						label={__('Show Date', 'interactive-related-posts')}
						checked={showDate}
						onChange={(value) => setAttributes({ showDate: value })}
					/>

					<ToggleControl
						label={__('Show Author', 'interactive-related-posts')}
						checked={showAuthor}
						onChange={(value) => setAttributes({ showAuthor: value })}
					/>

					<ToggleControl
						label={__('Show Category', 'interactive-related-posts')}
						checked={showCategory}
						onChange={(value) => setAttributes({ showCategory: value })}
					/>
				</PanelBody>

				{/* Query Settings Panel */}
				{selectedPosts.length === 0 && (
					<PanelBody title={__('Query Settings', 'interactive-related-posts')} initialOpen={false}>
						<SelectControl
							label={__('Order By', 'interactive-related-posts')}
							value={orderby}
							options={orderbyOptions}
							onChange={(value) => setAttributes({ orderby: value })}
						/>

						<SelectControl
							label={__('Order', 'interactive-related-posts')}
							value={order}
							options={orderOptions}
							onChange={(value) => setAttributes({ order: value })}
						/>
					</PanelBody>
				)}
			</InspectorControls>

			{/* Preview */}
			<div className="irp-block-preview">
				<ServerSideRender
					block="interactive-related-posts/related-posts"
					attributes={attributes}
				/>
			</div>
		</div>
	);
}
