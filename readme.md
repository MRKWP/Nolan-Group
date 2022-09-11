# Nolan Group Library Plugin
Nolan Group Library for core functions on the site by MRKWP.com

## Developers
- Matt
- Julius
- Elijah

### Tech Used
- Composer for autoloading and namespacing.

## Changelog

### 1.1.11
- Fix primary and secondary category

### 1.1.10
- Remove SKU from Import process.

### 1.1.9
- Added new external link field for both Brands and Nolan Products CPT

### 1.1.8
- Added Category Grid shortcode that displays name and featured image.
- Fix featured brand carousel user interface

### 1.1.7
- Swatch sync is now working and implemented as a post processing hook setup.

### 1.1.6
- Added a global contact CTA shortcode
- Added featured brand carousel

### 1.1.5
- Makers now delete old data before adding new to avoid duplications on re-run of schedule
- technical specs and features and benefits added to product import.

### 1.1.4
- Action scheduler and makers added for CSV Imports. Image Gallery import move to own process.

### 1.1.3
- Action scheduler and makers added for CSV Imports and Image import for Products

### 1.1.2
- Fix product carousel styles

### 1.1.1
- Added a filter for the product category pages that I want to filter the side bar on.
- Make use of a blocksy filter to turn off the side bar on pages that are relevant.

### 1.1.0
- Added Product Carousel and filter by brands, number of items and product category
- Implement gulp to compile scss to css

### 1.0.9
- Added new filters for brands and paginations
- added new actions for post types and product taxonomies

### 1.0.8
- Added breadcrumbs filter

### 1.0.7
- Added new links filter
- added readme file

### 1.0.6
- Original commit to add in the code.
- not sure on the other versions.

## Build Process
Uses Gulp Version 4.

### Steps to run
- Ensure you have Node & NPM installed.
- Install the **gulp-cli** globally using `npm install gulp-cli -g` in your terminal. Use `sudo` if needed for admin purposes.
- Run `npm install` to install all the sass dependencies as dev.
- Run `gulp style` -> to run css styles build on demand or `gulp watch` -> to continously watch your build.