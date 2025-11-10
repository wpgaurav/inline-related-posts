# Inline Related Posts

**Intelligently designed Inline Related Posts plugin for WordPress with shortcode and Gutenberg Block support.**

Display related posts anywhere on your WordPress site with multiple beautiful layouts, manual post selection, and flexible customization options.

## Features

- **Multiple Layout Styles**: Grid, Card, List, Thumbnail, Link-Only, and Minimal layouts
- **Dual Integration**: Works with both shortcodes and WordPress Gutenberg Blocks
- **Manual Post Selection**: Choose specific posts by ID or slug
- **Automatic Display**: Shows recent posts when no manual selection is made
- **Responsive Design**: Mobile-friendly layouts that adapt to all screen sizes
- **Customizable Columns**: 1-6 column layouts with responsive breakpoints
- **Rich Display Options**: Show/hide excerpts, dates, authors, categories
- **Query Flexibility**: Filter by category, tag, orderby, order
- **Developer Friendly**: Template override support in themes
- **Performance Optimized**: Lightweight and fast-loading

## Installation

1. Download or clone this repository into your WordPress plugins directory:
   ```bash
   cd wp-content/plugins
   git clone https://github.com/wpgaurav/inline-related-posts.git
   ```

2. Install npm dependencies and build the block:
   ```bash
   cd inline-related-posts
   npm install
   npm run build
   ```

3. Activate the plugin through the WordPress admin panel:
   - Go to **Plugins** → **Installed Plugins**
   - Find **Inline Related Posts** and click **Activate**

## Usage

### Using Shortcodes

#### Basic Usage

Display 6 recent posts in a 3-column grid:
```
[inline_related_posts]
```

Or use the short alias:
```
[irp]
```

#### Manual Post Selection

Select posts by IDs:
```
[inline_related_posts ids="1,2,3,4,5,6"]
```

Select posts by slugs:
```
[inline_related_posts slugs="my-post-slug,another-post-slug"]
```

#### Layout Options

Choose from different layout styles:
```
[inline_related_posts layout="card" posts="8" columns="4"]
[inline_related_posts layout="list" posts="5"]
[inline_related_posts layout="thumbnail" columns="3"]
[inline_related_posts layout="link-only" posts="10"]
[inline_related_posts layout="minimal" posts="6"]
```

#### Display Customization

Control what information is displayed:
```
[inline_related_posts show_excerpt="true" show_date="true" show_author="true" show_category="true"]
[inline_related_posts show_excerpt="false" show_date="false"]
```

#### Query Filters

Filter posts by category or tag:
```
[inline_related_posts category="news" posts="6"]
[inline_related_posts tag="featured" posts="8"]
```

Order posts:
```
[inline_related_posts orderby="title" order="ASC"]
[inline_related_posts orderby="rand" posts="6"]
```

Exclude specific posts:
```
[inline_related_posts exclude="1,2,3"]
```

#### Complete Example

```
[inline_related_posts
    layout="card"
    posts="9"
    columns="3"
    show_excerpt="true"
    show_date="true"
    show_author="true"
    category="tutorials"
    orderby="date"
    order="DESC"
]
```

### Using WordPress Blocks

1. Open the WordPress Block Editor
2. Click the **+** button to add a new block
3. Search for **"Inline Related Posts"**
4. Click to insert the block

#### Block Settings

The block provides an intuitive interface in the sidebar:

**Post Selection Panel:**
- Search and manually select posts
- View selected posts
- Clear selections

**Layout Settings:**
- Choose layout style (Grid, Card, List, Thumbnail, Link-Only, Minimal)
- Adjust number of columns (1-6)

**Display Options:**
- Toggle excerpt visibility
- Toggle date display
- Toggle author display
- Toggle category display

**Query Settings (when no manual selection):**
- Set number of posts
- Order by (Date, Title, Random, Modified)
- Order direction (ASC/DESC)

## Available Layouts

### 1. Grid Layout (Default)
Displays posts in a clean grid with thumbnails, titles, excerpts, and meta information.

### 2. Card Layout
Beautiful card-style layout with large thumbnails, category badges, and call-to-action buttons.

### 3. List Layout
Horizontal list format with thumbnails on the left and content on the right.

### 4. Thumbnail Layout
Compact layout focusing on thumbnails with minimal text.

### 5. Link-Only Layout
Simple text links with optional dates - perfect for sidebar lists.

### 6. Minimal Layout
Clean, text-focused layout with subtle hover effects.

## Shortcode Attributes Reference

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `ids` | string | '' | Comma-separated post IDs |
| `slugs` | string | '' | Comma-separated post slugs |
| `posts` | number | 6 | Number of posts to display |
| `columns` | number | 3 | Number of columns (1-6) |
| `layout` | string | 'grid' | Layout style |
| `show_excerpt` | boolean | true | Show post excerpt |
| `show_date` | boolean | true | Show post date |
| `show_author` | boolean | false | Show post author |
| `show_category` | boolean | true | Show post category |
| `category` | string | '' | Filter by category slug |
| `tag` | string | '' | Filter by tag slug |
| `orderby` | string | 'date' | Order by (date, title, rand, modified) |
| `order` | string | 'DESC' | Order direction (ASC, DESC) |
| `exclude` | string | '' | Comma-separated post IDs to exclude |
| `post_type` | string | 'post' | Post type to query |
| `class` | string | '' | Additional CSS class |

## Customization

### Theme Template Override

You can override plugin templates in your theme:

1. Create directory: `your-theme/inline-related-posts/`
2. Copy template from `plugins/inline-related-posts/templates/`
3. Modify as needed

Example:
```
your-theme/
└── inline-related-posts/
    ├── card.php
    ├── grid.php
    └── custom.php
```

### Custom Styling

Add custom CSS to your theme:

```css
/* Custom card styling */
.irp-card-inner {
    border: 2px solid #your-color;
}

/* Custom hover effect */
.irp-grid:hover .irp-grid-item {
    transform: scale(1.05);
}
```

### Filters and Hooks

Customize available layouts:
```php
add_filter('irp_available_layouts', function($layouts) {
    $layouts[] = 'custom-layout';
    return $layouts;
});
```

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- Node.js 14+ (for development)

## Development

### Build Block Assets

```bash
# Development mode with watch
npm start

# Production build
npm run build
```

### Project Structure

```
inline-related-posts/
├── assets/
│   ├── css/
│   │   ├── styles.css      # Frontend styles
│   │   └── editor.css      # Block editor styles
│   └── js/
├── blocks/
│   ├── src/
│   │   ├── index.js        # Block registration
│   │   ├── edit.js         # Block editor component
│   │   └── save.js         # Block save (server-side)
│   └── build/              # Compiled assets
├── includes/
│   ├── class-irp-post-query.php      # Post query handler
│   ├── class-irp-template-loader.php # Template loader
│   ├── class-irp-shortcode.php       # Shortcode handler
│   └── class-irp-block.php           # Block handler
├── templates/
│   ├── card.php
│   ├── grid.php
│   ├── list.php
│   ├── thumbnail.php
│   ├── link-only.php
│   └── minimal.php
├── inline-related-posts.php  # Main plugin file
└── package.json
```

## License

GPL-2.0+

## Author

WP Gaurav - [GitHub](https://github.com/wpgaurav)

## Support

For issues, questions, or contributions, please visit:
https://github.com/wpgaurav/inline-related-posts/issues
