=== User Login Blocker ===
Contributors: fachudev
Tags: security, login, block, users, admin, access-control
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Block selected users from logging into your WordPress site with customizable messages and easy user management.

== Description ==

User Login Blocker is a simple yet powerful security plugin that allows administrators to selectively block specific users from logging into their WordPress site. Perfect for managing access control without deleting user accounts.

**Key Features:**

* **Block specific users** by selecting them from an easy-to-use dropdown interface
* **User role filtering** to quickly find users when you have many on your site
* **User search functionality** to locate users by name, email, or username
* **Customizable block message** that blocked users will see when attempting to log in
* **Easy unblock functionality** with individual unblock buttons
* **Clean admin interface** located in Settings > User Login Blocker
* **Secure implementation** with WordPress nonces and proper capability checks
* **Works with all user roles** including custom roles from themes and plugins

**Use Cases:**

* Temporarily block problematic users without deleting their accounts
* Restrict access during maintenance periods for specific user groups
* Manage access for ex-employees or former members
* Control access for testing or staging environments
* Block users for moderation purposes while preserving their content

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/user-login-blocker` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings > User Login Blocker screen to configure the plugin.

== Frequently Asked Questions ==

= Does this plugin delete user accounts? =

No, this plugin only blocks login access. User accounts and all their associated data remain intact.

= Can blocked users still appear on the site? =

Yes, their content and profile information remain visible. This plugin only prevents login access.

= Does it work with custom user roles? =

Yes, the plugin automatically detects and works with all user roles, including custom ones created by themes and plugins.

= Can I block administrators? =

Yes, but be careful! Make sure you have another way to access your site before blocking admin users.

= Is there a limit to how many users I can block? =

No, you can block as many users as needed.

== Screenshots ==

1. Main settings page with user selection and filtering options
2. Filter users by role dropdown for easy user location
3. Search functionality to find users by name, email, or username
4. Currently blocked users list with individual unblock buttons
5. Custom block message configuration

== Changelog ==

= 1.2 =
* Added user role filtering functionality for easier user selection
* Added user search functionality to find users by name, email, or username
* Combined filtering options that work together for precise results
* Improved UI with organized filter controls and clear filters button
* Enhanced submit button text from 'Save Changes' to 'Block Selected Users'
* Better user experience for sites with many users
* Support for all user roles including custom roles from themes/plugins

= 1.1 =
* Added unblock functionality with individual unblock buttons for each blocked user
* Improved admin interface with better visual layout for blocked users list
* Added confirmation dialogs for unblock actions
* Enhanced security with proper nonce verification for unblock operations
* Better handling of invalid user IDs in blocked list

= 1.0 =
* Initial release
* Basic user blocking functionality
* Customizable block messages
* Clean admin interface

== Upgrade Notice ==

= 1.2 =
This version adds powerful filtering and search capabilities, making it much easier to manage users on sites with many users. Enhanced UI and better user experience.