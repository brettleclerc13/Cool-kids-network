<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'cool-kids-database' );

/** Database username */
define( 'DB_USER', 'test_bleclerc' );

/** Database password */
define( 'DB_PASSWORD', 'password123' );

/** Database hostname */
define( 'DB_HOST', 'mariadb:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', 'utf8_general_ci' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'uU?Bo?jLX3*y0Ip}T~[&>4)*z^06cF~Gt4lZ4aj&j;.</exBB;L{3|3e[ppZ[J.p' );
define( 'SECURE_AUTH_KEY',   '#HK|EtaCzlH[Ix VDQ.CLIX&Pi,(B,m`YJ}zAT+#CP_X-=!{a ;i_qp*SR nvu#U' );
define( 'LOGGED_IN_KEY',     'o[@PpbPQx&G!CXZq|mMc!4k0.kxx7 ff53CZ!bw?U[zz[nqIj1jG7dpQicsFIyGa' );
define( 'NONCE_KEY',         'z@R5,5gj=+auY}SQLo<O~-RRFNAlt]60i? `x/4ana;;=[C+q;N*ZCO@x,&|Oh`H' );
define( 'AUTH_SALT',         'U{g[W{PU&{Jp7m6?BDIwmQ:`0C*xC+i^-awM >7&,fU[u}TgovzHZ8NE:%BK3&:1' );
define( 'SECURE_AUTH_SALT',  '<iBWI8[?0y_R1>z_#0?pC#?c[!A KeFL1u&]u4fvXiDFSLm/Eg!-{f>]Yu: K?xX' );
define( 'LOGGED_IN_SALT',    'PawM1KBr~:[kx*d(qSv7O7*m}J~+.?Tonwk[YPXwhPgA -WF?TyZm;k#B8yCd){#' );
define( 'NONCE_SALT',        'A]P4uAme9_L6|$ocd&|ppUjaG/OrAOF]vxxx}/xwbQ>D t@<Qu+2Sf&v<dc/e;hC' );
define( 'WP_CACHE_KEY_SALT', 'A|<34G~<wz!<9*IR%#pjylt{q>mZ?,O;>(( m88b0Vzw0I_V?F$=|y=9<CiJP0mu' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
