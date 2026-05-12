# WishesKit

Wishes & RSVP plugin for Elementor. Let guests leave wishes and confirm attendance with a beautiful, fully customizable widget.

## Description

WishesKit is a WordPress plugin that integrates with Elementor to provide a beautiful wishes/comments section with RSVP (attendance confirmation) functionality. Perfect for wedding invitations, events, and celebrations.

### Core Features:

* Elementor widget with full drag-and-drop support
* RSVP confirmation: Hadir, Tidak Hadir, Masih Ragu
* Counter boxes showing real-time attendance stats
* Reply to comments (threaded/nested)
* Avatar with name initials
* AJAX-powered (no page reload)
* Responsive design

### Success Popup Dialog:

* Customizable popup after successful comment submission
* Configurable icon, title, and message (soft-selling copywriting)
* WhatsApp CTA button with pre-filled message
* Discount code display with copy-to-clipboard functionality
* All elements can be toggled on/off independently

### Admin Features:

* Comments stored in WordPress native comments system
* RSVP status column visible in WordPress admin comments dashboard
* Edit and delete comments directly from the frontend (admin only)
* Server-side duplicate submission protection

### Pagination:

* Prev/Next pagination for comments
* Configurable comments per page (default: 15)
* Custom button text for prev/next

### Full Customization (Elementor Style Tab):

* Title: color, typography
* Counter Boxes: background, text color, border color, number typography, label typography
* Form: input background, text color, border color, border radius, typography
* Submit Button: background, text color, border radius, typography, padding
* Comments: author color/typography, text color/typography, date color, reply link color
* Avatar: background color, text color
* RSVP Badges: individual colors for hadir, tidak hadir, ragu
* Pagination: button background, text color, info color, typography
* Success Popup: overlay color, background, border radius, icon color/size, title color/typography, message color/typography, close button colors, WhatsApp button colors, discount box colors/typography

## Installation

1. Upload the `wisheskit` folder to `/wp-content/plugins/` or install via WordPress Plugin Uploader (Plugins > Add New > Upload Plugin)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make sure Elementor is installed and active
4. Edit a page with Elementor, search for "WishesKit" widget in the widget panel
5. Drag the widget to your page and customize via Content and Style tabs

## Frequently Asked Questions

**Does this plugin require Elementor?**

Yes, WishesKit is designed as an Elementor widget. You need Elementor (free or Pro) installed and active.

**Where are the comments stored?**

All comments are stored in the WordPress native comments system. You can manage them from WordPress Admin > Comments. A custom RSVP column shows the attendance status.

**Can guests reply to other comments?**

Yes, threaded/nested replies are supported. Guests can click "Reply" on any comment to respond.

**Can I customize the popup dialog?**

Yes, everything in the popup is customizable from the Elementor panel: icon, title, message, WhatsApp button (number + pre-filled message), discount code, and close button text. All colors and typography are also adjustable.

**Is the edit/delete feature available for all users?**

No, edit and delete buttons only appear for logged-in users with the `moderate_comments` capability (administrators and editors).

**How does pagination work?**

Comments are paginated with Prev/Next buttons. Default is 15 comments per page, configurable from the Content tab in Elementor. Pagination auto-hides when there's only one page.

## Screenshots

1. Widget form with name, message, and RSVP dropdown
2. Counter boxes showing attendance stats
3. Comments list with avatar initials and RSVP badges
4. Reply form for threaded comments
5. Success popup with WhatsApp CTA and discount code
6. Elementor Style tab customization options

## Changelog

### 1.2.0
* Added pagination (prev/next) with configurable per-page limit
* Added pagination style controls in Elementor
* Version bump to force browser cache refresh

### 1.1.0
* Fixed duplicate comments caused by browser cache
* Added server-side duplicate submission protection
* Added edit and delete comment functionality (admin only)
* Updated popup copywriting to soft-selling for conversion
* Fixed counter box sizing to be equal width/height
* Set default margin to 20px across all elements

### 1.0.0
* Initial release
* Wishes form with name, message, RSVP
* Counter boxes (Hadir, Tidak Hadir, Masih Ragu)
* Threaded replies
* Avatar initials
* Success popup with WhatsApp CTA and discount code
* Full color and typography customization via Elementor

## Upgrade Notice

### 1.2.0
Adds pagination for comments. Recommended update for sites with many wishes/comments.
