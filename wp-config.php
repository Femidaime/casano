<?php
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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'clan' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '{F:d-qmEvmx|Pf4L`=RP{jwP~lxhP)ts4 U:IU8,1NxsX50{^62]o&h?,/ 1{tR#' );
define( 'SECURE_AUTH_KEY',  '/-~MFwV*$t+bwpVy2 !|*+O[r>@vx3[?6o(,CIyG9{5aWL%|5k2qgob7E[cN-+z-' );
define( 'LOGGED_IN_KEY',    '+a&>R0grW]vDZ~tk6bP>xWrD4;|g3d7W=uGHp8-+{(bF|`F.Jq^ZUCf.#0T5 gNJ' );
define( 'NONCE_KEY',        'J@Z<] VOKx`-&9a%!++A@R6w`(h8H<%G#{2M7y/T[Rf8/Gi#!U%&DZ%zC)z%:GN8' );
define( 'AUTH_SALT',        'uV1-ZsfpzM.o9tf:F/4Oa6}Kf2vmN*g;kQ|;7T!5^<JVT6``;xgI``.;qW!?NpmH' );
define( 'SECURE_AUTH_SALT', 'V|mXXs:~bmr{$y3DfU3N!j0)m}1k-;f9X5le&3{ .0S<3+:X/-$z1JdTlSXTwi0Q' );
define( 'LOGGED_IN_SALT',   'xUb&mTelv~ SRtF|0FwrD eaQR|m(;}IK+ nezsY,+=xavzcq|H,*N9!Yk0]s(])' );
define( 'NONCE_SALT',       'mC^;# 6~c9[ZyVV8X):MG^o&N`Q#Pu@@V~& #9p/ghs0Y2vMYWS|cq&=f*R!J;|4' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
