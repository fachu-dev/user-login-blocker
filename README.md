# User Login Blocker

**Version:** 1.2  
**Author:** Fachu Dev  
**License:** GPL v2 or later  

A WordPress plugin that allows administrators to selectively block users from logging into the site. Blocked users will see a customizable message when they attempt to log in.

## Features

- **Block specific users** by selecting them from a dropdown list in the admin panel
- **User role filtering** to easily find users when you have many users on your site
- **User search functionality** to quickly locate users by name, email, or username
- **Customizable block message** that appears when blocked users try to log in
- **Easy management** with a clean admin interface in Settings > User Login Blocker
- **User-friendly display** showing usernames, emails, and user IDs for easy identification
- **Unblock functionality** to easily remove users from the blocked list
- **Secure implementation** with WordPress nonces and proper capability checks
- **Support for all user roles** including custom roles created by themes and plugins

## Installation

### Method 1: Upload via WordPress Admin (Recommended)

1. Download the latest release ZIP file from this repository
2. In your WordPress admin, go to **Plugins > Add New**
3. Click **Upload Plugin**
4. Choose the downloaded ZIP file and click **Install Now**
5. Click **Activate Plugin**

### Method 2: Manual FTP Upload

1. Download and extract the plugin files
2. Upload the `user-login-blocker` folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the **Plugins** menu in WordPress

### Method 3: Git Clone (Development)

```bash
cd /path/to/wp-content/plugins/
git clone https://github.com/fachu-dev/user-login-blocker.git
```

Then activate through the WordPress admin.

## Usage

1. After activation, go to **Settings > User Login Blocker** in your WordPress admin
2. **Filter users** (optional): Use the role dropdown and/or search field to find specific users
3. **Select users to block**: Choose users from the filtered dropdown list (hold Cmd/Ctrl to select multiple)
4. **Customize message** (optional): Set the message that blocked users will see
5. **Block users**: Click the "Block Selected Users" button
6. **Unblock users**: Use the "Unblock" button next to each blocked user in the list

### Finding Users Easily
When you have many users on your site, use the filtering options:
- **Role Filter**: Select a role from the dropdown to show only users with that role
- **Search**: Enter text to search by name, email, or username
- **Combined Filters**: Use both filters together for more precise results
- **Clear Filters**: Click "Clear Filters" to reset and show all users again

Both filters work with all roles including custom roles created by themes and plugins.

Blocked users will see your custom message when they try to log in, and the login will be prevented.

## Changelog

### Version 1.2
- Added user role filtering functionality for easier user selection
- Added user search functionality to find users by name, email, or username
- Combined filtering options that work together for precise user location
- Improved user interface with organized filter controls and clear button
- Enhanced user experience when searching for specific users on large sites
- Support for all user roles including custom roles created by themes and plugins
- Better organization of admin interface with comprehensive filtering capabilities

### Version 1.1
- Added unblock functionality with individual unblock buttons for each blocked user
- Improved admin interface with better visual layout for blocked users list
- Added confirmation dialogs for unblock actions
- Enhanced security with proper nonce verification for unblock operations
- Better handling of invalid user IDs in blocked list

### Version 1.0
- Initial release
- Basic user blocking functionality
- Customizable block messages
- Clean admin interface

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher

## Support

If you encounter any issues or have questions about this plugin, please open an issue on this repository.

## License

This plugin is licensed under the GPL v2 or later.

## Screenshots

- Admin interface for managing blocked users
- Blocked attempts log view
- Settings configuration panel

## Configuration

The plugin requires no initial configuration. Once activated, you can immediately start adding blocked items through the admin interface.

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher

## Changelog

### Version 1.0
- Initial release
- Email blocking functionality
- IP address blocking functionality
- Username pattern blocking functionality
- Blocked attempts logging
- Admin interface for managing blocks

## Support

For support, please create an issue on the GitHub repository or contact the developer.

## License

This plugin is licensed under the GPL v2 or later.

## Developer

Developed by Facundo Criscuolo
- Website: [fachu.dev](https://fachu.dev)
- Email: criscuolo.facu@gmail.com

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.