# 04 - Desarrollo de Widgets para Dashboards

En **Zabbix 7.0 LTS y 7.4**, los widgets de dashboard tienen una arquitectura altamente optimizada, asíncrona y basada en componentes orientados a eventos.

---

## Estructura de un Widget de Dashboard

```text
modules/mi_widget/
├── manifest.json            # Metadatos, tipo "widget" y clase JS
├── Widget.php               # Inicialización del widget (opcional)
├── actions/
│   └── WidgetView.php       # Controlador que procesa los datos mostrados
├── views/
│   └── widget.view.php      # Vista HTML del contenido del widget
├── includes/
│   └── WidgetForm.php       # Campos configurables por el usuario
└── assets/
    ├── css/
    │   └── widget.css       # Estilos CSS encapsulados
    └── js/
        └── class.widget.js  # Lógica JavaScript extendiendo CWidget
```

---

## 1. Formulario de Configuración (`includes/WidgetForm.php`)

Define los campos que el usuario puede editar cuando añade o modifica el widget en su dashboard (ej. títulos, selección de hosts, colores, límites):

```php
<?php

namespace Modules\LmClockWidget\Includes;

use Zabbix\Widgets\CWidgetForm;
use Zabbix\Widgets\Fields\CWidgetFieldTextBox;
use Zabbix\Widgets\Fields\CWidgetFieldColor;

class WidgetForm extends CWidgetForm {

    public function addFields(): self {
        return $this
            ->addField(
                (new CWidgetFieldTextBox('custom_text', _('Texto descriptivo')))
                    ->setDefault('Mi Dashboard')
            )
            ->addField(
                (new CWidgetFieldColor('bg_color', _('Color de fondo')))
                    ->setDefault('2B303A')
            );
    }
}
```

---

## 2. Controlador de Vista (`actions/WidgetView.php`)

Recoge los valores guardados en el formulario y prepara los datos para renderizar:

```php
<?php

namespace Modules\LmClockWidget\Actions;

use CControllerDashboardWidgetView;
use CControllerResponseData;

class WidgetView extends CControllerDashboardWidgetView {

    protected function doAction(): void {
        $custom_text = $this->fields_values['custom_text'] ?? '';
        $bg_color    = $this->fields_values['bg_color'] ?? 'ffffff';

        $this->setResponse(new CControllerResponseData([
            'name'          => $this->getInput('name', $this->widget->getName()),
            'custom_text'   => $custom_text,
            'bg_color'      => $bg_color,
            'user'          => [
                'debug_mode' => $this->getDebugMode()
            ]
        ]));
    }
}
```

---

## 3. Vista del Widget (`views/widget.view.php`)

Renderiza el contenido HTML que se inserta dentro del contenedor del widget:

```php
<?php

use CDiv;
use CTag;

(new CDiv([
    (new CDiv($data['custom_text']))->addClass('widget-title-custom'),
    (new CDiv())->addClass('clock-display')
]))
    ->addClass('dashboard-widget-content')
    ->show();
```

---

## 4. Lógica JavaScript (`assets/js/class.widget.js`)

Extiende la clase nativa `CWidget` de Zabbix. Gestiona el ciclo de vida del widget en el navegador:

```javascript
class WidgetLmClock extends CWidget {

    onInitialize() {
        super.onInitialize();
        this._interval = null;
    }

    onStart() {
        super.onStart();
        this._startClock();
    }

    onDeactivate() {
        super.onDeactivate();
        if (this._interval) {
            clearInterval(this._interval);
        }
    }

    _startClock() {
        const display = this._body.querySelector('.clock-display');
        if (!display) return;

        const updateTime = () => {
            const now = new Date();
            display.textContent = now.toLocaleTimeString();
        };

        updateTime();
        this._interval = setInterval(updateTime, 1000);
    }

    // Se ejecuta cada vez que el widget cambia de tamaño en la cuadrícula
    onResize() {
        super.onResize();
        // Ajustar fuentes o gráficos si es necesario
    }

    // Se ejecuta cuando Zabbix trae datos nuevos tras el refresco del widget
    processUpdateResponse(response) {
        super.processUpdateResponse(response);
    }
}
```

---

## 5. Reglas de Estilo CSS Scoped (`assets/css/widget.css`)

Para evitar que los estilos de tu widget colisionen con el resto del frontend o con otros widgets, Zabbix estandariza selectores basados en el `id` del módulo:

```css
/* Selector para el contenedor del widget con id 'lm_clock_widget' */
div.dashboard-widget-lm_clock_widget {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    padding: 12px;
}

div.dashboard-widget-lm_clock_widget .clock-display {
    font-size: 2.2rem;
    font-weight: bold;
    color: #1976d2;
    font-family: monospace;
}
```
