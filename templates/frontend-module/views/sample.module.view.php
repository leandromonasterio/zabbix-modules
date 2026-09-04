<?php

use CHtmlPage;
use CDiv;
use CTableInfo;
use CSpan;

/**
 * Vista renderizada por SampleModuleView.
 * La variable $data contiene los valores inyectados por el controlador.
 */

$page = (new CHtmlPage())
    ->setTitle($data['module_name']);

// Caja de información principal
$info_box = (new CDiv([
    (new CDiv($data['description']))->addClass('module-description'),
    (new CDiv([
        new CSpan(_('Hora del servidor: ')),
        (new CSpan($data['server_time']))->addClass('server-time-value')
    ]))->addClass('module-meta')
]))->addClass('module-hero-card');

$page->addItem($info_box);

// Tabla de ejemplo con estilos nativos
$table = (new CTableInfo())
    ->setHeader([
        _('Parámetro'),
        _('Valor'),
        _('Estado')
    ])
    ->addRow([
        _('Módulo'),
        $data['module_name'],
        (new CSpan(_('Operativo')))->addClass(ZBX_STYLE_GREEN)
    ]);

$page->addItem($table);

// Muestra la página completa con el layout nativo de Zabbix
$page->show();
