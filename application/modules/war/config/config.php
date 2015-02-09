<?php
/**
 * @package ilch
 */

namespace Modules\War\Config;

defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'war',
        'author' => 'Stantin Thomas',
        'icon_small' => 'war.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'War',
                'description' => 'Hier kannst du die Wars verwaltet werden.',
            ),
            'en_EN' => array
            (
                'name' => 'War',
                'description' => 'Here you can manage the wars.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_war`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_war_groups`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_war_enemy`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_war_groups`
                (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `tag` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `image` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `member` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    PRIMARY KEY (`id`)
                )   ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_war_enemy`
                (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `tag` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `homepage` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `logo` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `land` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `contact_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `contact_email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    PRIMARY KEY (`id`)
                )   ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_war`
                (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `enemy` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `group` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `time` datetime NOT NULL,
                    `maps` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `server` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `password` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `xonx` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `game` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `matchtype` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `report` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `status` tinyint(1) NOT NULL DEFAULT 0,
                    PRIMARY KEY (`id`)
                )   ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
    }
}