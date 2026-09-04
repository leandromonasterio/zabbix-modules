<?php

namespace Modules\TemplateNamespace\Actions;

use CController;
use CControllerResponseData;
use CControllerResponseFatal;

/**
 * Controlador de la vista principal del módulo.
 */
class SampleModuleView extends CController {

    public function init(): void {
        // En acciones informativas/GET de sólo lectura, se desactiva la validación CSRF
        $this->disableCsrfValidation();
    }

    protected function checkInput(): bool {
        // Define las reglas de validación para los parámetros recibidos
        $fields = [
            'status' => 'in 0,1'
        ];

        $ret = $this->validateInput($fields);

        if (!$ret) {
            $this->setResponse(new CControllerResponseFatal());
        }

        return $ret;
    }

    protected function checkPermissions(): bool {
        // Permite la ejecución a cualquier usuario autenticado en el frontend
        return $this->getUserType() >= USER_TYPE_ZABBIX_USER;
    }

    protected function doAction(): void {
        // Lógica de negocio y preparación de datos para la vista
        $data = [
            'module_name' => _('TemplateModuleName'),
            'description' => _('TemplateModuleDescription'),
            'server_time' => date('Y-m-d H:i:s')
        ];

        $this->setResponse(new CControllerResponseData($data));
    }
}
