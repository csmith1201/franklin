FRANKLIN, Readme File
---------------------------------------------------------------------------
2009-06-19, [Marcel Eichner // Ephigenia](love@ephigenia.de)

<img src="http://marceleichner.de/static/img/public/ca1920e3/620xauto/resize/franklin_creenshot_safari_iphone.jpg" />

# DESCRIPTION

Franklin is a very basic PHP5-script that collects data from various tests that
you’ve configured from websites and services for a later display as charts in a
report page.

# REQUIREMENTS

* PHP 5.2 or later
* PHP Curl module (e.g. "php5-curl" package) 

# INSTALLATION

1. copy all files into an extra directory in your webspace
2. make the directory `/app/data` writable by the user running cron.php
(see below)
3. cp `/app/config/config.php.dist` to `/app/config/config.php` and edit it
4. create a cron job that calls cron.php periodically (e.g. every 10mins)  
   example: `*/10 * * * * user  php5 -f /path/to/cron.php 2>&1 > /dev/null`
5. open the Franklin html directory in your webbrowser to see the reports

# TESTS

* `name` Optional name for a test, used also as chart title
* `interval` time interval between checks for the current test.  
  f.e. `+1 day`, `+30 minutes` or shorter `2h`

## Facebook Group Fan Count

Detect the number of fans of a facebook group or page. This uses the open graph
API and therefore the page must be public (not set to private and no age-
restriction).

* `groupId` id or name of the facebook group or page, copy the id or name from
the uri when you’re on the facbeook page

## Feedburner Subscribers

Number of [Feedburner](http://www.feedburner.com)-Feed subscribers.
Make sure you enabled the Awareness API in the Feedburner configuration.

* `uri` Uri of the Feedburner-Feed, just copy the uri part of the URL

## Feedburner Hits

Number of Hits on a [Feedburner](http://www.feedburner.com)-Feed.
Make sure you enabled the Awareness API in the Feedburner configuration.

* `uri` Uri of the Feedburner feed, you can simply get it from a feedburner
  URL

## Google Results Count

Number of results on a google search query. You can use this to get the number
of indexed pages or backlinks to your website that are known to google. You 
also can make up custom queries.

* `search` query to search
  f.e. `site:www.horrorblog.org` or `link:www.horrorblog.org`

## Google SERP

This test can really help you to track the results of your SEO arrangements by
telling you on which position (from 1 to 64) your website is on when you search
for a specific term.
It uses the Google Search API and can give you result also language and country
specific by using the needed parameters.

* `search` the term you want to search for, usually a keyword you want to track
* `language` optional 2-letter language code you want to search in, i.e. `de`
  or `en`
* `country` optional additional country code you want to search in, this is
 automatically the same as language
* `save` optional save mode parameter that can be one of the 3 options
  specified in the google search api documentation: `active`, `moderate` and
  `off`, default is `active`

## Lesercharts.de Position

Get the position on lesercharts.de depending on the url of the website.

* `search` Name of the entry on lesercharts.de, it’s usually the name in the 
  first column

## Twitter Followers Count

Detect the number of followers of a twitter user

* `username` name of the twitter user to check, not the real name of the user
  name is the uri part in the twitter profile url.  
  f.e. `www.twitter.com/horrorblogorg` -> `username` would be `horrorblogorg`

## Twitter Search API Results Count

This test uses the [Twitter Search API](http://apiwiki.twitter.com/Twitter-Search-API-Method%3A-search) to search
the twitter site for a specific query (`q`). You can limit the results by date
(`since`) or location (`locale`, `geocode`). The test will always record the
number of results found.

* `q` Query search, parameter, check the Twitter Search API for examples
* `since` Since parameter to limit search results for since a date, a week
  or some days are recommended.  
* `geocode`
* `locale`

## Google Analytics API

This for now rather basic test exports pageViews or visits from google
analytics data to franklin. Please see the `config.php.dist` file for an
example.

# UPDATES/FEEDBACK

Franklin will be updated from time to time. Check the official [Franklin
Homepage](http://code.marceleichner.de/project/franklin) and the [github
page](http://github.com/Ephigenia/franklin) for updates, new tests or contact
to the developers.

# Changelog

* 2010-10-15
	* Devided Back and Frontend, set your webroot to `/html/` directory! Also
	take care that the data directory change, copy your data files there!
	* Refactored project structure to have one public html directory
	* Refactored theming
	* Added Twitter Search API Results counter for recording number of
	twitter followers, mentions and other stuff.
	* Added Google Analytics Data API Test for recording pageViews and
	visits on a google analytics profile

* 2010-10-07
	* made sure a filename for the data files only contains valid characters

* 2010-08-21
	* Added possibility to add display configiration for tests
	* Some tests have `number` as their default display (rollover shows 
	chart)
	* chart data is shiftet to minimum to see progress zoomed

* 2010-06-21
	* Fixed bug in Google SERP Test where name of test was set after
	  constructing class and same file used, so test was ignoring interval

* 2010-06-20
	* Added google SERP (Search Engine Results Page) Test that can test on
	  which position your site is on when you search for a term in a country
	  and language, see the example on how to configure it.
	* Also added a very simple and static Log class that logs the results of 
	  all tests when in DEBUG_DEBUG or larger, modify your Franling::$debug
	  if you want to surpress the messages.
	* Fixed bug where chart was not rendered when chart title inclueded a
	  quotation mark, now escaped with urlencode

* 2010-06-16
	* Added Test that records the number of facebook group members
	* Fixed Twitterfollowers Regexp
	* Added some documentation for some of the most used Tests
	
* 2010-06-15
	* Fixed bug when no data available (empty charts)
	* Deleted old charting classes in `lib/chart`

* 2010-06-09
	* Updated all charts to use google chart API instead of own library
	* Added light and dark skin that can be changed by hand
	
* 2010-05-15
	* Fixed Google Results Count Test using Google Ajax REST API now
	* Fixed Alexa Site Rank test

* 2010-03-06
	* Added Test fro lesercharts.de
	* Fixed Alexa Traffic Rank for country
	* Cron now reports how many tests checked and runned

* 2009-10-17
	* Added Test for Twitter Follower Counting
	+ Added Test for recording Alexa Traffic Rank values
	* Changed code style to Zend Framework coding-guidelines

* 2009-06-19
	* Fixed nothing, just set up the first release on sourceforge.net
	* also put up a small website for franlin on sourceforge
