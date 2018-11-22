# ClassicPress Petitions Dashboard Widget
* Requires at least: 4.9.0
* Tested up to: 4.9.0
* Stable tag: 1.0.0


## Description

Find the latest petitions for ClassicPress growth.

## Installation
1. Upload the plugin to the `/wp-content/plugins/` directory or [install directly] from the repository.
1. Activate the plugin through the 'Plugins' menu in ClassicPress

## Frequently Asked Questions
1. How can I contribute?
* You can raise lots of issues here and also make some Pull Requests.

## Changelog

### Version 1.0.2 (22.11.2018)
* Conditionally remove events dashboard widget
* use `wp_remote_get()` instead of `file_get_contents()` to get API response. Ref #3

### Version 1.0.1 (22.11.2018)
* Used right API for most wanted features
* Class Self tests
* New variables to keep class code clean
* Limited the returned values to 10 only.
* Internationalization added.
* Styles the widget with default stylings in core

### Version 1.0.0 (21.11.2018)
* Initial Commit
