# 03 - Controladores, Permisos y Seguridad (CSRF)

Los controladores en Zabbix son clases que gestionan la lógica de negocio detrás de cada acción registrada en `manifest.json`. Todas las clases de controlador deben extender `CController` y ubicarse dentro de la carpeta `actions/`.

---

## Estructura de un Controlador (`CController`)

Cada controlador debe implementar de forma obligatoria cuatro métodos esenciales:

```php
<?php

namespace Modules\LmQuickLinks\Actions;

use CController;
use CControllerResponseData;
use CControllerResponseFatal;
use CRoleHelper;

class QuickLinksView extends CController {

    /**
     * 1. init(): Inicialización del controlador.
     * Configuración de CSRF o deshabilitación si es una vista de sólo lectura.
     */
    public function init(): void {
        // En vistas informativas GET se puede deshabilitar la validación CSRF:
        $this->disableCsrfValidation();
    }

    /**
     * 2. checkInput(): Validación estricta de parámetros GET/POST recibidos.
     * Si retorna false, la ejecución se interrumpe antes de ejecutar la acción.
     *
     * @return bool
     */
    protected function checkInput(): bool {
        $fields = [
            'category'  => 'string',
            'limit'     => 'int32|ge 1|le 100'
        ];

        $ret = $this->validateInput($fields);

        if (!$ret) {
            $this->setResponse(new CControllerResponseFatal());
        }

        return $ret;
    }

    /**
     * 3. checkPermissions(): Comprobación de roles y privilegios de usuario.
     * Retorna true si el usuario actual tiene permiso para ejecutar la acción.
     *
     * @return bool
     */
    protected function checkPermissions(): bool {
        // Permitir a cualquier usuario con acceso a la UI:
        return $this->checkAccess(CRoleHelper::UI_MONITORING_HOSTS);

        // O restringir sólo a Super Administradores:
        // return $this->getUserType() == USER_TYPE_SUPER_ADMIN;
    }

    /**
     * 4. doAction(): Lógica principal de negocio.
     * Obtiene datos, procesa y los envía a la vista mediante CControllerResponseData.
     */
    protected function doAction(): void {
        $category = $this->getInput('category', 'general');

        // Los datos deben organizarse en un array asociativo:
        $data = [
            'title'     => _('Enlaces Rápidos'),
            'category'  => $category,
            'items'     => [
                ['name' => 'Wiki Interna', 'url' => 'https://wiki.empresa.com'],
                ['name' => 'Grafana', 'url' => 'https://grafana.empresa.com']
            ]
        ];

        // Se envía a la vista especificada en manifest.json
        $this->setResponse(new CControllerResponseData($data));
    }
}
```

---

## Validación de Parámetros de Entrada (`checkInput`)

Zabbix cuenta con un motor de validación declarativa muy robusto. Puedes especificar tipos y restricciones en una sola cadena:

| Regla | Descripción | Ejemplo |
| :--- | :--- | :--- |
| `string` | Cadena de texto | `'name' => 'string'` |
| `not_empty` | No puede estar vacío | `'title' => 'string\|not_empty'` |
| `int32` | Entero de 32 bits | `'hostid' => 'int32'` |
| `ge N` | Mayor o igual que N | `'limit' => 'int32\|ge 1'` |
| `le N` | Menor o igual que N | `'limit' => 'int32\|le 50'` |
| `in A,B,C` | Valores permitidos (Enum) | `'status' => 'in 0,1,2'` |
| `required` | Campo obligatorio | `'token' => 'required\|string'` |

---

## Protección CSRF (Cross-Site Request Forgery)

Por diseño de seguridad en Zabbix:
- **Acciones con Modificación de Datos (POST, Crear, Borrar, Guardar)**:
  - Mantén habilitada la validación CSRF (no llames a `$this->disableCsrfValidation()`).
  - En las vistas con formularios, debes incluir el token CSRF obligatorio:
  ```php
  $form = (new CForm())
      ->cleanItems()
      ->addItem((new CVar(CCsrfTokenHelper::CSRF_TOKEN_NAME, CCsrfTokenHelper::get())));
  ```
- **Acciones de Sólo Lectura (GET, Vistas, Reportes)**:
  - En el método `init()`, ejecuta `$this->disableCsrfValidation();` para evitar errores de token al navegar directamente o refrescar la página.

---

## Niveles de Usuario y Permisos

Zabbix define tres tipos de usuario a través de constantes globales:
1. `USER_TYPE_ZABBIX_USER` (Usuario estándar)
2. `USER_TYPE_ZABBIX_ADMIN` (Administrador)
3. `USER_TYPE_SUPER_ADMIN` (Super Administrador)

Para verificar el tipo:
```php
if ($this->getUserType() < USER_TYPE_ZABBIX_ADMIN) {
    return false;
}
```
Para verificar acceso granular según las reglas del rol asignado en Zabbix 7:
```php
return $this->checkAccess(CRoleHelper::UI_ADMINISTRATION_GENERAL);
```
