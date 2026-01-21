# Easy-Cart CSS Architecture

This project uses a modular CSS architecture with separate files organized by concern for better maintainability and performance.

## 📁 File Structure

```
css/
├── base.css          # Reset, CSS variables, typography, global styles
├── layout.css        # Header, footer, navigation, containers
├── components.css    # Cards, buttons, forms, tables, grids
├── pages.css         # Page-specific styles
├── utilities.css     # Utility classes and helper styles
└── responsive.css    # Media queries and responsive design
```

## 📄 File Descriptions

### `base.css`
- CSS custom properties (variables)
- Global reset and box-sizing
- Typography styles and font definitions
- Base element styles

### `layout.css`
- Container and layout utilities
- Header and navigation styles
- Footer styles
- Page wrapper and main content areas

### `components.css`
- Reusable component styles:
  - Cards (`.card`, `.product-card`)
  - Buttons (`.btn`, `.btn-primary`, `.btn-secondary`, `.btn-ghost`)
  - Forms (`.form-input`, `.form-label`, `.form-fieldset`)
  - Tables (`.table-container`, table styles)
  - Grid systems (`.product-grid`, `.category-grid`)
- Product-specific components

### `pages.css`
- Page-specific overrides and styles
- Cart page styles (`.price-summary`)
- Auth page styles (login/signup)
- Order page styles
- Checkout page styles

### `utilities.css`
- Utility classes for common patterns
- Spacing utilities (margins, padding)
- Display utilities (flex, block, hidden)
- Text alignment utilities
- Accessibility utilities (`.sr-only`)

### `responsive.css`
- Media queries for different screen sizes
- Mobile-first responsive design
- Print styles
- Accessibility features (reduced motion, focus styles)

## 🎨 CSS Variables

All colors, spacing, and common values are defined as CSS custom properties in `base.css`:

```css
:root {
  --bg-primary: #ffffff;
  --bg-secondary: #fafafa;
  --accent: #ff6b35;
  --text-primary: #1a1a1a;
  --border: #e5e5e5;
  /* ... more variables */
}
```

## 🚀 Usage

The main `style.css` file imports all modular CSS files:

```css
@import url('css/base.css');
@import url('css/layout.css');
@import url('css/components.css');
@import url('css/pages.css');
@import url('css/utilities.css');
@import url('css/responsive.css');
```

## 📱 Responsive Breakpoints

- **1024px and below**: Large tablets
- **768px and below**: Tablets and small laptops
- **640px and below**: Mobile devices

## 🎯 Benefits

1. **Modularity**: Each file has a single responsibility
2. **Maintainability**: Easy to find and modify specific styles
3. **Performance**: Can load specific CSS files per page if needed
4. **Scalability**: Easy to add new components and pages
5. **Collaboration**: Multiple developers can work on different CSS files
6. **Organization**: Clear separation of concerns

## 🔧 Development

When adding new styles:

1. **Base styles** → `base.css`
2. **Layout changes** → `layout.css`
3. **New components** → `components.css`
4. **Page-specific styles** → `pages.css`
5. **Utility classes** → `utilities.css`
6. **Responsive adjustments** → `responsive.css`

## 📋 Naming Conventions

- Use BEM methodology for component classes
- Use lowercase with hyphens for multi-word classes
- Prefix page-specific classes with page name when needed
- Use semantic class names that describe purpose

## 🎨 Color Palette

- **Primary**: Orange (#ff6b35) - for accents and primary actions
- **Background**: White (#ffffff) and light gray (#fafafa)
- **Text**: Dark gray (#1a1a1a) for primary, medium gray (#666666) for secondary
- **Borders**: Light gray (#e5e5e5)
- **Success/Warning/Error**: Standard semantic colors

This architecture makes the codebase more maintainable, scalable, and developer-friendly while keeping the same visual design and functionality.