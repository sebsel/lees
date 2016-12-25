<?php
/*
 * This is the config template.
 * Rename to /config/config.php
 */

// Filter read items from the feed
c::set('filter-read', true);

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
