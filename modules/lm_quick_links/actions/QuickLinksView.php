<?php

namespace Modules\LmQuickLinks\Actions;

use CController;
use CControllerResponseData;
use CControllerResponseFatal;

/**
 * Controlador de la página Quick Links Hub.
 */
class QuickLinksView extends CController {

    public function init(): void {
        // Al ser una página de consulta (sólo lectura), desactivamos validación CSRF
        $this->disableCsrfValidation();
    }

    protected function checkInput(): bool {
        // Validador de parámetros GET opcionales para filtrar por categoría
        $fields = [
            'category' => 'in all,monitoring,tools,docs'
        ];

        $ret = $this->validateInput($fields);

        if (!$ret) {
            $this->setResponse(new CControllerResponseFatal());
        }

        return $ret;
    }

    protected function checkPermissions(): bool {
        // Accesible para cualquier usuario autenticado en Zabbix
        return $this->getUserType() >= USER_TYPE_ZABBIX_USER;
    }

    protected function doAction(): void {
        $selected_category = $this->getInput('category', 'all');

        // Catálogo de enlaces operativos predeterminados
        $all_links = [
            [
                'name'        => 'Documentación Oficial Zabbix',
                'description' => 'Manuales de referencia para Zabbix 7.0 LTS y 7.4.',
                'url'         => 'https://www.zabbix.com/documentation/current',
                'category'    => 'docs',
                'badge'       => 'Oficial',
                'badge_style' => ZBX_STYLE_GREEN
            ],
            [
                'name'        => 'Zabbix Integrations & Templates',
                'description' => 'Repositorio oficial de plantillas e integraciones listas para usar.',
                'url'         => 'https://www.zabbix.com/integrations',
                'category'    => 'tools',
                'badge'       => 'Comunidad',
                'badge_style' => ZBX_STYLE_BLUE
            ],
            [
                'name'        => 'initMAX Modules Wiki',
                'description' => 'Documentación y utilidades de módulos UI desarrollados por initMAX.',
                'url'         => 'https://www.initmax.com/wiki/',
                'category'    => 'tools',
                'badge'       => 'Partner',
                'badge_style' => ZBX_STYLE_ORANGE
            ],
            [
                'name'        => 'GitHub - Repositorio de Módulos',
                'description' => 'Repositorio personal de módulos desarrollados para Zabbix.',
                'url'         => 'https://github.com/leandromonasterio/zabbix-modules',
                'category'    => 'docs',
                'badge'       => 'GitHub',
                'badge_style' => ZBX_STYLE_GREY
            ]
        ];

        // Filtrado dinámico por categoría si corresponde
        $filtered_links = [];
        foreach ($all_links as $link) {
            if ($selected_category === 'all' || $link['category'] === $selected_category) {
                $filtered_links[] = $link;
            }
        }

        $data = [
            'title'             => _('Quick Links Hub'),
            'selected_category' => $selected_category,
            'links'             => $filtered_links,
            'total_count'       => count($filtered_links)
        ];

        $this->setResponse(new CControllerResponseData($data));
    }
}
