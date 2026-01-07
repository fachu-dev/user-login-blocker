# User Login Blocker

A WordPress plugin to block user login attempts based on email, IP address, or username patterns.

## Description

User Login Blocker is a security plugin that allows administrators to prevent specific users or IP addresses from logging into their WordPress site. This plugin provides flexible blocking options and detailed logging of blocked attempts.

## Features

- **Email Blocking**: Block login attempts by specific email addresses
- **IP Address Blocking**: Block login attempts from specific IP addresses
- **Username Pattern Blocking**: Block login attempts based on username patterns
- **Detailed Logging**: Keep track of all blocked login attempts
- **Easy Administration**: Simple admin interface to manage blocked users and view logs
- **Security Enhancement**: Add an extra layer of security to your WordPress site

## Installation

1. Upload the `user-login-blocker` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Settings' > 'User Login Blocker' to configure the plugin

## Usage

### Adding Blocked Items

1. Navigate to **Settings > User Login Blocker**
2. Choose the type of block:
   - **Email**: Enter the email address to block
   - **IP Address**: Enter the IP address to block
   - **Username**: Enter the username pattern to block
3. Click "Add Block"

### Viewing Blocked Attempts

1. Go to the **Blocked Attempts Log** tab
2. View all blocked login attempts with timestamps and details
3. Use the "Clear Log" button to reset the log if needed

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