<?php

namespace Modules\TemplateNamespace;

use Zabbix\Core\CModule;
use APP;
use CMenuItem;

/**
 * Clase principal de inicialización del módulo.
 */
class Module extends CModule {

    /**
     * Se ejecuta durante la carga del módulo.
     * Inserta la entrada correspondiente en el menú de Zabbix.
     */
    public function init(): void {
        APP::Component()->get('menu.main')
            ->findOrAdd(_('Monitoring'))
            ->getSubmenu()
            ->add(
                (new CMenuItem(_('TemplateModuleName')))
                    ->setAction('template.action.view')
            );
    }
}
