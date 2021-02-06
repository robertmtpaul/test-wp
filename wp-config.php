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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'lawfirm' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:8889' );

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
define( 'AUTH_KEY',         '#EVa ~[[5<Ux`Y0@~Y>rSe,R).Ylzfci7FeZh/QhM7Mw6*E1PHttr*e}`,-[7i@D' );
define( 'SECURE_AUTH_KEY',  'IlBfjvNj{O-OVHnB_DC8cq`x}|w!7 Mf8(?-kG{6ZYLHmE+iC$K$3C:FkG,viURu' );
define( 'LOGGED_IN_KEY',    'q|Ys]^UyDm&DL1(1xDj=PQZ@,GWhRZ+c,;,}u@3N4a%J!2psvT{,>efX:cjdZSB+' );
define( 'NONCE_KEY',        'MsXwKYmGnislnXwkib$eVnhnUpOZfS_O)&-IM<{s!b4r1b;Oky LJ{n?_X@OU!Sz' );
define( 'AUTH_SALT',        '[~;kf<L|1nNq1k5Ar5Hrp5n5~HAQSA_Vv{Z?F&5d=9.fl 4Z+AVO~q84dm]v>s!H' );
define( 'SECURE_AUTH_SALT', '!We%jHKYs1;.b.O`E9X_Y+EKd0d,4y*_iTf]Q/b<y$?d%+@l@u4s,Nj`orGUWj*j' );
define( 'LOGGED_IN_SALT',   'wwVCF;j:E&-NZ.@86xGjQ8_*9<60.DIU){sODq_Yf;G|my:)Btg(i@~E6VpW|#6K' );
define( 'NONCE_SALT',       'Oc7ul|he HH^tTV|/LT9gfKtcXH|+;X{&BbH~PLG?;DJKGU&W_;8G%TkrIj$9N^j' );

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
