# 02 - Ciclo de Vida del Módulo y Manipulación de Menús

En un módulo estándar de Zabbix, la clase principal reside en el archivo `Module.php` en la raíz del directorio del módulo y extiende `Zabbix\Core\CModule`.

---

## Ciclo de Vida y Eventos (Hooks)

Un módulo habilitado se ejecuta en **cada solicitud HTTP** entrante antes del código de la acción solicitada. Dispone de tres métodos principales que puedes sobrescribir:

```php
<?php

namespace Modules\LmModuloEjemplo;

use Zabbix\Core\CModule;
use CController;

class Module extends CModule {

    /**
     * Se ejecuta durante la inicialización del módulo.
     * Ideal para inyectar entradas en el menú principal o registrar servicios.
     */
    public function init(): void {
        // Inicialización de componentes o menús
    }

    /**
     * Hook ejecutado ANTES de que se procese cualquier acción del controlador.
     *
     * @param CController $action Instancia del controlador que se va a ejecutar.
     */
    public function onBeforeAction(CController $action): void {
        $action_name = $action->getAction();
        // Permite inspeccionar la acción solicitada o aplicar filtros globales
    }

    /**
     * Hook ejecutado al finalizar la solicitud, justo antes de terminar el script.
     *
     * @param CController $action Instancia del controlador ejecutado.
     */
    public function onTerminate(CController $action): void {
        // Tareas de limpieza o registro de auditoría final
    }
}
```

---

## Manipulación del Menú Principal

Zabbix estructura el menú principal a través del componente `menu.main`. Mediante `Module.php`, puedes agregar nuevos menús principales, submenús anidados o enlazar con páginas existentes.

### 1. Agregar una Entrada en la Raíz del Menú

```php
<?php

namespace Modules\LmQuickLinks;

use Zabbix\Core\CModule;
use APP;
use CMenuItem;

class Module extends CModule {

    public function init(): void {
        // Obtiene el menú principal e inserta un nuevo botón raíz
        APP::Component()->get('menu.main')
            ->add(
                (new CMenuItem(_('Enlaces Rápidos')))
                    ->setAction('lm.quicklinks.view')
                    ->setIcon('icon-help') // o clases de icono de Zabbix
            );
    }
}
```

### 2. Insertar una Entrada dentro de una Sección Existente (ej. *Monitoring*)

Si deseas que tu módulo aparezca dentro de una categoría oficial como **Monitoring** (*Monitorización*) o **Reports** (*Informes*):

```php
<?php

namespace Modules\LmQuickLinks;

use Zabbix\Core\CModule;
use APP;
use CMenuItem;

class Module extends CModule {

    public function init(): void {
        // Busca la sección 'Monitoring' y añade el item después de 'Hosts'
        APP::Component()->get('menu.main')
            ->findOrAdd(_('Monitoring'))
            ->getSubmenu()
            ->insertAfter(
                _('Hosts'),
                (new CMenuItem(_('Enlaces de Guardia')))
                    ->setAction('lm.quicklinks.view')
            );
    }
}
```

### 3. Crear Submenús de Tercer Nivel

Puedes crear menús desplegables con múltiples páginas dependientes:

```php
<?php

namespace Modules\LmQuickLinks;

use Zabbix\Core\CModule;
use APP;
use CMenu;
use CMenuItem;

class Module extends CModule {

    public function init(): void {
        APP::Component()->get('menu.main')
            ->findOrAdd(_('Reports'))
            ->getSubmenu()
            ->add(
                (new CMenuItem(_('Operaciones Andreani')))
                    ->setSubMenu(
                        new CMenu([
                            (new CMenuItem(_('Dashboards Operativos')))
                                ->setAction('lm.ops.dashboards'),
                            (new CMenuItem(_('Contactos de Escalado')))
                                ->setAction('lm.ops.contacts')
                        ])
                    )
            );
    }
}
```

---

## Internacionalización (`_('Texto')`)

Es fundamental envolver siempre todos los textos visibles del usuario en la función de traducción `_('Tu texto aquí')`. Esto permite que Zabbix aplique las traducciones a los más de 25 idiomas compatibles con el frontend.
