# Medical Exemption WordPress Plugin

A WordPress plugin that receives a date and two optional arguments to retrieve and display medical exemption information from a database.

## Description

This plugin provides functionality to query and display medical exemption data based on a date parameter and optional arguments. The plugin retrieves relevant information from a configured database and presents it to users via shortcodes or custom endpoints.

## Features

- **Date-based Querying**: Accepts a date parameter to filter medical exemption records
- **Optional Arguments**: Two additional optional parameters for more refined searches
- **Database Integration**: Retrieves data from a configured database
- **Flexible Display**: Outputs information in customizable formats

## Installation

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the database settings in the plugin settings page (if applicable)

## Usage

### Shortcode

Use the shortcode to display medical exemption information:

```
[medical_exemption date="YYYY-MM-DD" arg1="value1" arg2="value2"]
```

**Parameters:**
- `date` (required): The date to query in YYYY-MM-DD format
- `arg1` (optional): First optional argument
- `arg2` (optional): Second optional argument

**Example:**
```
[medical_exemption date="2024-07-29"]
[medical_exemption date="2024-07-29" arg1="category" arg2="status"]
```

### PHP Function

You can also use the plugin programmatically:

```php
<?php
if (function_exists('get_medical_exemption_data')) {
    $data = get_medical_exemption_data(
        '2024-07-29',
        'arg1_value',
        'arg2_value'
    );
    // Process $data
}
?>
```

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- MySQL/MariaDB database access

## Configuration

1. Navigate to **Settings > Medical Exemption** in your WordPress admin panel
2. Configure database connection settings (if needed)
3. Set up any additional parameters required for your use case

## Database Schema

The plugin expects a database table with medical exemption records. Ensure your database table includes:
- Date field for filtering
- Fields corresponding to the optional arguments
- Medical exemption data fields

## Development

### File Structure

```
medical-exemption-plugin/
├── medical-exemption.php      # Main plugin file
├── includes/
│   ├── class-database.php     # Database handler
│   ├── class-shortcode.php    # Shortcode handler
│   └── class-admin.php        # Admin settings
├── assets/
│   ├── css/
│   └── js/
└── README.md
```

## Support

For issues, questions, or contributions, please use the repository's issue tracker.

## License

[Specify your license here - e.g., GPL v2 or later]

## Changelog

### 1.0.0
- Initial release
- Date-based querying functionality
- Two optional arguments support
- Database integration
- Shortcode implementation

## Author

[Your Name/Organization]

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

