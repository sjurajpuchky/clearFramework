<?php

/**
 * Application payway.cz
 * @author Juraj Puchký - Devtech
 * @copyright (c) 2015 Devtech
 * @date 10.1.2015
 */
include_once __DIR__.'/../config.php';
include_once __DIR__.'/i18n.php';
include_once __DIR__.'/db.php';
include_once __DIR__.'/seo.php';
include_once __DIR__.'/ad.php';
include_once __DIR__.'/FormField.php';
include_once __DIR__.'/Select.php';
include_once __DIR__.'/Form.php';
include_once __DIR__.'/MenuItem.php';
include_once __DIR__.'/Redir.php';
include_once __DIR__.'/Hidden.php';
include_once __DIR__.'/Menu.php';
include_once __DIR__.'/Table.php';
include_once __DIR__.'/ContentControler.php';
include_once __DIR__.'/Tools.php';
include_once __DIR__.'/Mailer.php';

$pwdb = new cf\Db ();
