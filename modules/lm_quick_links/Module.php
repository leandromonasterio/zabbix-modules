<?php

namespace Modules\LmQuickLinks;

use Zabbix\Core\CModule;
use APP;
use CMenuItem;

/**
 * Módulo de Enlaces Rápidos (Quick Links Hub).
 * Registra el acceso directo en el menú de Zabbix.
 */
class Module extends CModule {

    /**
     * Inicializa el módulo agregando una opción dentro de la sección 'Monitoring'.
     */
    public function init(): void {
        APP::Component()->get('menu.main')
            ->findOrAdd(_('Monitoring'))
            ->getSubmenu()
            ->insertAfter(
                _('Hosts'),
                (new CMenuItem(_('Quick Links')))
                    ->setAction('lm.quicklinks.view')
            );
    }
}
