<?php

namespace Modules\{{MODULE_NAMESPACE}}\Actions;

use CControllerDashboardWidgetView;
use CControllerResponseData;

/**
 * Controlador de vista para el widget en el dashboard.
 */
class WidgetView extends CControllerDashboardWidgetView {

    protected function doAction(): void {
        $custom_message = $this->fields_values['custom_message'] ?? 'Zabbix';

        $this->setResponse(new CControllerResponseData([
            'name'           => $this->getInput('name', $this->widget->getName()),
            'custom_message' => $custom_message,
            'timestamp'      => time(),
            'user'           => [
                'debug_mode' => $this->getDebugMode()
            ]
        ]));
    }
}
