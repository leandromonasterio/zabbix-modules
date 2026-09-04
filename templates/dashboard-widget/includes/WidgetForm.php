<?php

namespace Modules\TemplateNamespace\Includes;

use Zabbix\Widgets\CWidgetForm;
use Zabbix\Widgets\Fields\CWidgetFieldTextBox;

/**
 * Define los campos del formulario modal de configuración del widget.
 */
class WidgetForm extends CWidgetForm {

    public function addFields(): self {
        return $this
            ->addField(
                (new CWidgetFieldTextBox('custom_message', _('Mensaje personalizado')))
                    ->setDefault('Zabbix Widget Activo')
            );
    }
}
