<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'wpuser' );

/** Database password */
define( 'DB_PASSWORD', 'wpp' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'L?D`H9N2>pC)PaGd<F5n9IV:w!J2z=v4%ij3Od3(t|2;Kz2)=2P]VQ%p-vz0k[JR' );
define( 'SECURE_AUTH_KEY',  'gbw0&oTYH/[JnKXC-%6IN/H?IoYGTd2Rih=i4W[l+nqn3[q}jFN&gK+glLadh)7%' );
define( 'LOGGED_IN_KEY',    'C1`B!_<LRU>ZHEy|KRhU,.?O@Sa:g1(|ohZLXXVR73y`()Q:+ajY)wIolN,wFWH1' );
define( 'NONCE_KEY',        'WQqf<s,Izadro&T6^n(rQ-6y7XRO70/`RSd~0<VR/b}9>)?*a#>A=NJ`EUI(>3h+' );
define( 'AUTH_SALT',        'l?V|B.-RC,WZL|j_>Z%qz_33)-_Pn;Pg:I{i_9Pt&E/$<`/v3xhxE^z6@j5PFln}' );
define( 'SECURE_AUTH_SALT', ')R;VywsM`d Ni%)6l%alreiaZdlQt:F~Wi]9&>Dj1rZn*}jFK-)0{!vg%D%@^QC2' );
define( 'LOGGED_IN_SALT',   '5lE]~i]# 9?r?6^%seC:xU!g@(hnPA&};g?$;x2hEF>UX&uY83_=&wZUa)v[.Rj3' );
define( 'NONCE_SALT',       'D>R9]^BG.`.Bp/z:)#Ffa]gK_FJ>dZT#XCkBlxQ#Mm`t}Np]1N?_*%8kl!$-F Y~' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_live';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */


define( 'FS_METHOD', 'direct' );

/* That's all, stop editing! Happy publishing. */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
