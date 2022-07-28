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
          `nb_product`      int(10) unsigned NOT NULL DEFAULT '1',
          `show_arrow`      tinyint(1) unsigned NOT NULL DEFAULT '1',
          `show_bullet`     tinyint(1) unsigned NOT NULL DEFAULT '1',
          `assoc_object`    enum('category', 'manufacturer') NOT NULL DEFAULT 'category',
          `id_object`       int(10) unsigned NOT NULL DEFAULT '0',
          `date_add`        datetime NOT NULL,
          `date_upd`        datetime NOT NULL,
          PRIMARY KEY (`id_carrousel`)
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8";
// Install table for slide
$sql[] = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "lk_carrousel_lang` (
        `id_carrousel`   int(10) unsigned NOT NULL,
        `title`          VARCHAR(120) NOT NULL,
        `id_lang`         int(10) unsigned NOT NULL
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8";

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return $sql;
    }
}
