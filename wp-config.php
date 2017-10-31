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
define('DB_NAME', 'travelsiquijor');

/** MySQL database username */
define('DB_USER', 'admin');

/** MySQL database password */
define('DB_PASSWORD', 'admin1937');

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
define('AUTH_KEY',         'lK0*1LzR:xmfU82)4{9|}GBrj(M)WHYr&ILuX<9$X=eU?[!Bi#u3gdx!n11nkS/m');
define('SECURE_AUTH_KEY',  '5ecbCiMZYEc;Lj)!b`O:U8t@9Ks0$k:su*9c1,j#75yi`tw./(+)T%ET!eiIiJyd');
define('LOGGED_IN_KEY',    'A_sep}q[<awgOW;I2rp?+:lKhfS*CzP{ l`q(!l }?mLmv$l0kW|}re!!m@L^R(b');
define('NONCE_KEY',        'T@Huxn+G<,O:Ytu@1DBmG]|#zc4k8W1T$Owb5fJv!z)&#hpxz!zjF!0w:.0:[xwN');
define('AUTH_SALT',        '1_tfJrV-Es+#RY/.:E#f<R;VX|NxQa)AJ!29b`Q;$ffEE)~Oz:&sem.nM%]G7x%J');
define('SECURE_AUTH_SALT', '!1(V^WvO7E[r5`qn]dLEe<czSsN+Ngl&ad{,#V.^,$m=<6$W_V!1ZfJYUaYT+v<0');
define('LOGGED_IN_SALT',   'YS0{)<D4Zk15HPHazUzymwEV+9saL>e_!M[_^2e~9%MyJK]4-URz=lK;bB1~<:`*');
define('NONCE_SALT',       'VuxemS(9G09$HTANeIyb;q)B>D1EWC:Y&O5UvoABxN;2:cRuHn06SL/R5sH^WqM]');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
