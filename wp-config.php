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
define('DB_NAME', 'olu');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'im@rk123#@');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'Vym:y(7Gw=vHEp i@QnxPO-abTNzP 8>fXHo`Uz~fX)c~v({y#}sv~<EQ@A:4B.}');
define('SECURE_AUTH_KEY', 'U+<R17{95*+1QM>PFO92So LIZuQV5c8P7;U9a6b(AXK]=wl$1ZaKj:wg}<!@zNX');
define('LOGGED_IN_KEY', 'p=qH&n`AyrJ?!8(L@p=hH2~hy=cGEK@TP32C:E^#:C?<f/uD$R?Pk?_,ks_4`T#<');
define('NONCE_KEY', '$8L4X4Vj.PbH}qYkcX#(q+Ip9|X@]]THcNrvxZ=NI.kHr%~y4mV=`w{dF-PB{Gu&');
define('AUTH_SALT', 'zOJ;7lLhk_O 4?@K h{`@!1]f0IL<P0<*$|qmVZ:&;FwJ0Lit+>l6cJY6/xOWkvT');
define('SECURE_AUTH_SALT', ']l0-u&LH-s 9vz/EZ,(W+mD<&.Rj!d[|pBYKQHy,hI9dFnGP[KR?o(9=>_I 8)&;');
define('LOGGED_IN_SALT', '3P6JH23b^=3J}`soGZ:D>qZi4I6XW{*o[PoIwys1zRxT6qwt%)v;k9^^_tkxxS_6');
define('NONCE_SALT', 'fHvN_1~m^hP3kfpoX@5QVXiduARML$w-Gy<Oc`#BT5RT:4IC4oJO)7AEbWpPg% =');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wtw_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH'))
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
