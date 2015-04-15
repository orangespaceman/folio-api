# folio-api

API for my new 'folio

## Installation

Install composer:

```
curl -s http://getcomposer.org/installer | php
```

Install packages:

```
php composer.phar install
```

Visit the site in a browser...


## Config

There is a sample config file in the root of the repo: `config.sample.php`

This file needs to be renamed (or duplicated) to `config.php` and add the appropriate keys needed for API access

## Cache

The cache directory may need `777` permissions


## API Endpoints

(Visit the home route in a browser for a list of available routes)


## Technologies

 - [Slim Framework](http://www.slimframework.com/)
 - [Composer](https://getcomposer.org/)
 - [File System Cache](https://github.com/jdorn/FileSystemCache)
 - [Spotify Web API (PHP)](http://jwilsson.github.io/spotify-web-api-php/)

## APIs & Feeds

 - [Flickr](https://www.flickr.com/services/feeds/)
 - [GitHub](https://developer.github.com/v3/)
 - [Goodreads](https://www.goodreads.com/api)
 - [Last.fm](http://www.last.fm/api/feeds)
 - [Spotify](https://developer.spotify.com/web-api/)