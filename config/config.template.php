<?php
/*
 * This is the config template.
 * Rename to /config/config.php
 */

// Locale and language settings
c::set('language', 'en');

// Filter read items from the feed
c::set('filter-read', true);

// DB settings, see Kirby's docs
c::set('db.type', 'sqlite');
c::set('db.name', dirname(__DIR__).DS.'data'.DS.'database.db');
// CREATE TABLE 'entry' ('id' TEXT PRIMARY KEY NOT NULL, 'status' TEXT)
// with an index on 'status'

// Your IndieAuth-enabled url
c::set('user', 'https://yourindieauthurl.example/');

// Some random stuff for the cookie, set a new one for yourself
c::set('salt', 'hExijmxB4RhoY14VaOpxgC4WDDHgOI');

// Micropub for likes, bookmarks - Only set one if you have one
# c::set('micropub-endpoint', 'http://example.com/micropub');
# c::set('micropub-access-token', '...');
// TODO: let this thing obtain access tokens automatically

// Twitter access token for https://granary-demo.appspot.com
# c::set('twitter-access-token-key', '');
# c::set('twitter-access-token-secret', '');
// TODO: use granary as library and generate tokens yourself
