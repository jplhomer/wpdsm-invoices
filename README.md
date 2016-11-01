# Sample Plugin for Unit Testing Presentation

This is a sample plugin for the [presentation I gave on Unit Testing](https://docs.google.com/presentation/d/1A5Umbl7E25i5Xii3FEnq5TS88UipcwAVSgPmjiCtoVE/edit?usp=sharing) to WordPress Des Moines on October 31, 2016.

## Running Tests

PHPUnit is required to run the tests, as well as a local WordPress install with a database.

1. Clone this folder to the plugins folder of an active WordPress install
1. Install the test suite:

		$ sh bin/install-wp-tests.sh dbname dbuser dbpass dbhost

1. Run tests:

		$ phpunit
