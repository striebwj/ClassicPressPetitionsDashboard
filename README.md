# ClassicPress Petitions Dashboard Widget
* Requires at least: 4.9.8
* Tested up to: 4.9.8
* Stable tag: 1.0.3

## Description
Find the latest petitions for ClassicPress growth.

## Installation
1. Upload the plugin to the `/wp-content/plugins/` directory ~~or install directly from the repository~~.
1. Activate the plugin through the 'Plugins' menu in ClassicPress

## Screeshots
![Screen shot of Top 10 ClassicPress Petitions in Dashboard with tab navigation](https://github.com/bahiirwa/ClassicPressPetitionsDashboard/blob/master/assets/images/Screenshot-1.png "Screen shot of Top 10 ClassicPress Petitions in Dashboard with tab navigation")

## Frequently Asked Questions
1. How can I contribute?
* You can raise lots of issues here and also make some Pull Requests.

## TODO
1. Get the first button to colorize like tab. Make tab navigation more obvious. (Line 101)
1. Repetition for the data tables html. (Line 136-155, 163-174, 185-198)

## Changelog

### Version 0.1.3 (02.12.2018)
* New Tab Navigation for petitions
* Refactored Code 
* Proper Versioning

### Version 0.1.3 (27.11.2018)
* Proper Version Numbers
* Added Screenshots.

### Version 0.1.2 (22.11.2018)
* Conditionally remove events dashboard widget
* use `wp_remote_get()` instead of `file_get_contents()` to get API response. Ref #3

### Version 0.1.1 (22.11.2018)
* Used right API for most wanted features
* Class Self tests
* New variables to keep class code clean
* Limited the returned values to 10 only.
* Internationalization added.
* Styles the widget with default stylings in core

### Version 0.1.0 (21.11.2018)
* Initial Commit
