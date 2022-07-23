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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'wordpress' );

/** Database password */
define( 'DB_PASSWORD', 'wordpress' );

/** Database hostname */
define( 'DB_HOST', 'db01.kosmovskiy.ru' );

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
define( 'AUTH_KEY',         'MxjV}9Xgl@|QaW8*PKu~<~fw<lNM4oAa!`0*TOE}O`aL5Z_RAHIO]]-q5l?&uuLk' );
define( 'SECURE_AUTH_KEY',  ')uT$x2i/>fXe!P#n`=PIqJx/0qo:CcK$R,l9ry@p?flBw%?:M`lq==GRX*+YM&*|' );
define( 'LOGGED_IN_KEY',    '2g)YXLg#K+V^h@]Ugw.Oo34/w+8zSB_RO3vdJVD~SRE+`]~Zzw.Np~L}Oa{MLK/T' );
define( 'NONCE_KEY',        'Sy-kS^)]x,&jt>NYMgDad1xqI-HqRvGJ0 IL *SS:jhl++1-:y)n87>,J8U$g:.@' );
define( 'AUTH_SALT',        '>dn*r$JB*&fX: M*_|rIu)`J0Lo[2W8-PZ}sb]~L9,V>[>^|XUV8U$=J7.x4(DW7' );
define( 'SECURE_AUTH_SALT', 'i;9+9he.Jovmm]Tc!HF.a^Pqx1#GmuLW60%A`{0[OKm`-DuU$UA9_[juH.[)@Du4' );
define( 'LOGGED_IN_SALT',   ')~`))a#}BgyDyzK*Ha;OU~)hFPS`)46Q,<.yO5{6hT_Qc!Q9)!(^O}<PRlz!R)L?' );
define( 'NONCE_SALT',       'W,8ulcFU^TjVq$0$8~ND(W4#ci?FY$:%|BR)C2^sL6x$`?~|>)S%V98n?SG0v7$#' );

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
        define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';