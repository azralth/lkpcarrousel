<?php
/**
 *  Copyright (C) Lk Interactive - All Rights Reserved.
 *
 *  This is proprietary software therefore it cannot be distributed or reselled.
 *  Unauthorized copying of this file, via any medium is strictly prohibited.
 *  Proprietary and confidential.
 *
 * @author    Lk Interactive <contact@lk-interactive.fr>
 * @copyright 2007.
 * @license   Commercial license
 */

$sql = array();
// Install table for slider
$sql[] = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "lk_carrousel` (
          `id_carrousel`   int(10) unsigned NOT NULL AUTO_INCREMENT,
          `position`        int(10) unsigned NOT NULL DEFAULT '0',
          `active`          tinyint(1) unsigned NOT NULL DEFAULT '1',
          `hook`          VARCHAR(255) NOT NULL,
          `order_by`          VARCHAR(255) NOT NULL,
          `sort_order`          VARCHAR(255) NOT NULL,
          `nb_product`      int(10) unsigned NOT NULL DEFAULT '1',
          `nb_product_to_show`      int(10) unsigned NOT NULL DEFAULT '1',
          `show_arrow`      tinyint(1) unsigned NOT NULL DEFAULT '1',
          `show_bullet`     tinyint(1) unsigned NOT NULL DEFAULT '1',
          `date_add`        datetime NOT NULL,
          `date_upd`        datetime NOT NULL,
          PRIMARY KEY (`id_carrousel`)
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8";
// Install table lang
$sql[] = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "lk_carrousel_lang` (
        `id_carrousel`   int(10) unsigned NOT NULL,
        `title`          VARCHAR(255) NOT NULL,
        `btn_title`          VARCHAR(255) NOT NULL,
        `id_lang`         int(10) unsigned NOT NULL
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8";

// Install table for category association
$sql[] = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "lk_carrousel_category_association` (
        `id_carrousel`   int(10) unsigned NOT NULL,
        `id_category`    int(10) unsigned NOT NULL
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8";

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return $sql;
    }
}
