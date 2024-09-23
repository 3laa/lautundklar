<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

define( 'ITSEC_ENCRYPTION_KEY', 'W2owSGo/KXk3XSNRZExiOHcuRE4xSVYrRy9VQC54RDZrK0hQKHw7VlFqLVBNJCtleEJYIX05ZUh2YE4tVE8+Mg==' );

define('WP_AUTO_UPDATE_CORE', false);// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'enfold_lautundklar' );
/** MySQL database username */
define( 'DB_USER', 'enfold_lautundklar' );
/** MySQL database password */
define( 'DB_PASSWORD', 'sm6#tJ380T^3z1xg72' );
/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '8+73]:!nVsU32X/hP#wwY*_8z_y4(m#Ye[7~C2wIfDF2XJbs+w14/M3M#ee2a1w9');
define('SECURE_AUTH_KEY', '3KQRN[@fQ7V2]FRea~W#vuXcDVZ406Th6g:*;(yf89ts1a6AG8|~vQN6azs@:83M');
define('LOGGED_IN_KEY', 'ct5Jvc1p:!ZvqK3!T3|#@R!0305NvFQ595JQe(56%~mZ9+kgW394&Nz(4rsT;Kz0');
define('NONCE_KEY', '!7RFUyC:)+Qj-N(8Lg6/PT_9:++wwx45jBPa(It#J53HtM@l3[;iA:~*%8rz#*hV');
define('AUTH_SALT', '7~]2qi;n4_3&Z2b-hAUthfy25V2NM_0t+aC87d(I0Rpyz774_7)gst1|V6ef0Z/I');
define('SECURE_AUTH_SALT', '|3ur88Ih814thw47&38J6827+l;ZC7#OMf7qkF*Jo~*7e4v64B9c_d&%-[R1O&f(');
define('LOGGED_IN_SALT', '@f_-%|yU@G64[&Et||HHz+57j!)VRcItCgr|#T7Ai97GOX-;5rtH1Ow2)6!v9iO4');
define('NONCE_SALT', 'tUAB6h:C]h0q1@YgOioLcIE6e/[TGWo4/187W1N*+udyr&l307LQMr;C@XWSY4|(');
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'luk_wp_';
define('WP_ALLOW_MULTISITE', false);
/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
/** Enables page caching for RapidLoad. */
if ( ! defined( 'WP_CACHE' ) ) {
	define( 'WP_CACHE', true );
}

define( 'WP_DEBUG', false );


/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

/* WP DEBUG */


