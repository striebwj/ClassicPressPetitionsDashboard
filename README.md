# ClassicPress Petitions Dashboard Widget
ClassicPress is a community run software. Get a quick view the latest, trending and most requested petitions from the ClassicPress community in the dashboard.

## Installation
1. Upload the plugin to the `/wp-content/plugins/` directory ~~or install directly from the repository~~.
1. Activate the plugin through the 'Plugins' menu in ClassicPress

## Requirements
* Requires ClassicPress: 4.9.8
* Tested up to: 4.9.8

## Screeshots
![Screen shot of Top 10 ClassicPress Petitions in Dashboard with tab navigation](https://github.com/bahiirwa/ClassicPressPetitionsDashboard/blob/master/assets/images/Screenshot-1.png "Screen shot of Top 10 ClassicPress Petitions in Dashboard with tab navigation")

## Frequently Asked Questions
1. How can I contribute?
* You can raise lots of issues here and also make some Pull Requests.

## Changelog

### Version 0.1.4 (03.12.2018)
* Tab Navigation styling clean up
* Static function introduced to remove html repetition

### Version 0.1.3 (02.12.2018)
* New Tab Navigation for petitions
* Refactored Code 
* Color Code statuses
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
