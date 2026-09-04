<?php

use CHtmlPage;
use CDiv;
use CTag;
use CList;
use CLink;
use CTableInfo;
use CSpan;
use CCol;

/**
 * Vista de Quick Links Hub.
 * Construida 100% con componentes nativos de Zabbix para compatibilidad de temas.
 */

$page = (new CHtmlPage())
    ->setTitle($data['title']);

// Filtro de categorías mediante tabs/pills
$categories = [
    'all'        => _('Todos'),
    'monitoring' => _('Monitoreo'),
    'tools'      => _('Herramientas'),
    'docs'       => _('Documentación')
];

$filter_nav = new CList();
$filter_nav->addClass('quick-links-filter-bar');

foreach ($categories as $cat_key => $cat_label) {
    $item_link = (new CLink($cat_label, (new CUrl('zabbix.php'))
        ->setArgument('action', 'lm.quicklinks.view')
        ->setArgument('category', $cat_key)
        ->getUrl()
    ))->addClass('filter-pill-link');

    if ($cat_key === $data['selected_category']) {
        $item_link->addClass('is-active');
    }

    $filter_nav->addItem($item_link);
}

$page->addItem((new CDiv($filter_nav))->addClass('quick-links-nav-wrapper'));

// Tabla principal de enlaces
$table = (new CTableInfo())
    ->setHeader([
        _('Nombre y Enlace'),
        _('Descripción'),
        _('Tipo'),
        _('Acción')
    ])
    ->setNoDataMessage(_('No hay enlaces disponibles en esta categoría.'));

foreach ($data['links'] as $link_item) {
    $external_link = (new CLink($link_item['name'], $link_item['url']))
        ->setTarget('_blank')
        ->setAttribute('rel', 'noopener noreferrer')
        ->addClass('quick-link-title');

    $badge = (new CSpan($link_item['badge']))
        ->addClass($link_item['badge_style']);

    $btn_open = (new CLink(_('Abrir ↗'), $link_item['url']))
        ->setTarget('_blank')
        ->setAttribute('rel', 'noopener noreferrer')
        ->addClass(ZBX_STYLE_BTN_LINK);

    $table->addRow([
        new CCol($external_link),
        new CCol($link_item['description']),
        new CCol($badge),
        new CCol($btn_open)
    ]);
}

$page->addItem($table);

// Renderizado final
$page->show();
