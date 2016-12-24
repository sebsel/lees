<?php
/*
 * This is the config template.
 * Rename to /config/config.php
 */

// Filter read items from the feed
c::set('filter-read', true);

// Micropub for likes, bookmarks - Only set one if you have one
# c::set('micropub-endpoint', 'http://example.com/micropub');
# c::set('micropub-access-token', '...');
// TODO: let this thing obtain access tokens automatically
