# User Login Blocker

**Version:** 1.2  
**Author:** Fachu Dev  
**License:** GPL v2 or later  

A WordPress plugin that allows administrators to selectively block users from logging into the site. Blocked users will see a customizable message when they attempt to log in.

## Features

- **Block specific users** by selecting them from a dropdown list in the admin panel
- **Block users by roles** including custom roles created by themes and plugins
- **Customizable block message** that appears when blocked users try to log in
- **Easy management** with a clean admin interface in Settings > User Login Blocker
- **User-friendly display** showing usernames, emails, and user IDs for easy identification
- **Unblock functionality** to easily remove users and roles from the blocked list
- **Secure implementation** with WordPress nonces and proper capability checks
- **Support for custom roles** automatically detects all available roles on the site

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

### Blocking Individual Users
1. After activation, go to **Settings > User Login Blocker** in your WordPress admin
2. Select users you want to block from the dropdown list (hold Cmd/Ctrl to select multiple)
3. To unblock users, use the **Unblock** button next to each blocked user in the list

### Blocking by User Roles
1. In the same settings page, scroll to the **Blocked roles** section
2. Check the roles you want to block (affects all users with those roles)
3. Custom roles created by themes/plugins are automatically detected and available
4. To unblock roles, use the **Unblock** button next to each blocked role in the list

### Custom Message
1. Customize the message that blocked users will see (optional)
2. Click **Save Changes**

Blocked users will see your custom message when they try to log in, and the login will be prevented.

## Changelog

### Version 1.2
- Added role-based blocking functionality
- Support for custom roles created by themes and plugins
- Automatic detection of all available roles on the site
- New admin interface section for managing blocked roles
- Individual unblock buttons for blocked roles
- Enhanced authentication logic to check both user IDs and roles
- Improved admin interface with better organization

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