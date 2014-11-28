<?php
Router::parseExtensions('rss');

Router::connect('/services', array('plugin' => 'gtw_servicess', 'controller' => 'services','action'=>'index'));
Router::connect('/services/index', array('plugin' => 'gtw_servicess', 'controller' => 'services','action'=>'index'));
Router::connect('/services/add', array('plugin' => 'gtw_servicess', 'controller' => 'services','action'=>'add'));
Router::connect('/services/edit/*', array('plugin' => 'gtw_servicess', 'controller' => 'services','action'=>'edit'));
Router::connect('/services/view/*', array('plugin' => 'gtw_servicess', 'controller' => 'services','action'=>'view'));
Router::connect('/services/delete/*', array('plugin' => 'gtw_servicess', 'controller' => 'services','action'=>'delete'));