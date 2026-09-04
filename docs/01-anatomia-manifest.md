# 01 - Anatomía del Archivo `manifest.json`

El archivo `manifest.json` es el punto de entrada obligatorio para cualquier módulo o widget en el frontend de Zabbix. Define la identidad, capacidades, controladores y recursos estáticos (CSS/JS) del componente.

A partir de Zabbix 6.4 y consolidado en **Zabbix 7.0 LTS / 7.4**, la especificación requerida es **`manifest_version: 2.0`**.

---

## Estructura General del Manifiesto

```json
{
  "manifest_version": 2.0,
  "id": "lm_modulo_ejemplo",
  "name": "Módulo de Ejemplo",
  "version": "1.0.0",
  "namespace": "LmModuloEjemplo",
  "author": "Leandro Monasterio",
  "description": "Descripción clara de lo que hace el módulo.",
  "url": "https://github.com/leandromonasterio/zabbix-modules",
  "type": "module",
  "actions": {
    "lm.modulo.view": {
      "class": "ModuloView",
      "view": "modulo.view",
      "layout": "layout.htmlpage"
    }
  },
  "assets": {
    "css": ["module.css"],
    "js": ["module.js"]
  },
  "config": {
    "parametro_inicial": "valor"
  }
}
```

---

## Detalle de Campos

| Campo | Tipo | Obligatorio | Descripción y Buenas Prácticas |
| :--- | :--- | :---: | :--- |
| `manifest_version` | Double | **Sí** | Debe ser `2.0`. |
| `id` | String | **Sí** | Identificador único en minúsculas y guiones bajos (ej. `lm_quick_links`). **Recomendación oficial:** usa un prefijo personal o de empresa (ej. `lm_`) para evitar colisiones con otros módulos. |
| `name` | String | **Sí** | Nombre amigable visible en *Administración → Módulos* y en el selector de widgets. |
| `namespace` | String | **Sí** | Espacio de nombres PHP (formato CamelCase, ej. `LmQuickLinks`). Las clases del módulo se cargarán bajo `Modules\<namespace>\...`. |
| `version` | String | **Sí** | Versión semántica del módulo (ej. `1.0.0`, `1.1.0`). Visible en el listado de administración. |
| `type` | String | No | `"module"` (por defecto) o `"widget"`. Si estás construyendo un widget de dashboard, **debe** ser `"widget"`. |
| `author` | String | No | Nombre del autor u organización. |
| `description` | String | No | Resumen de la funcionalidad del módulo. |
| `url` | String | No | Enlace a la documentación o repositorio. En los widgets, este enlace se abre al pulsar el botón de ayuda `?`. |
| `actions` | Object | No | Mapa de rutas/acciones que el módulo registra en el frontend. |
| `assets` | Object | No | Archivos CSS y JS adicionales requeridos por las vistas del módulo. |
| `config` | Object | No | Claves de configuración por defecto guardadas en la base de datos de Zabbix al registrar el módulo. |
| `widget` | Object | Solo Widget | Parámetros específicos para widgets de dashboard (dimensiones por defecto, clase JS, etc.). |

---

## Configuración de Acciones (`actions`)

Cada acción registrada en `actions` vincula una URL/acción de Zabbix con un controlador PHP (`CController`) y opcionalmente una vista.

### En Frontend Modules:
```json
"actions": {
  "lm.ejemplo.view": {
    "class": "EjemploView",
    "view": "ejemplo.view",
    "layout": "layout.htmlpage"
  }
}
```
- **Clave** (`lm.ejemplo.view`): Nombre de la acción invocado mediante `index.php?action=lm.ejemplo.view`. Debe ser en minúsculas separadas por puntos.
- `class`: Nombre de la clase del controlador PHP ubicada en el subdirectorio `actions/` (ej. `actions/EjemploView.php`).
- `view`: Nombre del archivo de vista ubicado en el subdirectorio `views/` (ej. `views/ejemplo.view.php`).
- `layout`: Define el contenedor global. Opciones:
  - `"layout.htmlpage"` (por defecto): Carga toda la cabecera, menú y pie de Zabbix.
  - `"layout.json"`: Para respuestas asíncronas / AJAX (API interna).
  - `null`: Para respuestas crudas (descargas directas de archivos, exports CSV, etc.).

### En Widgets de Dashboard (`type: "widget"`):
Para los widgets de dashboard, las claves de acción tienen un formato especial estándar:
```json
"actions": {
  "widget.lm_mi_widget.view": {
    "class": "WidgetView"
  }
}
```
- `widget.<id>.view`: Controlador que procesa los datos y renderiza el widget en el tablero.
- Por defecto carga `actions/WidgetView.php` y la vista `views/widget.view.php`.

---

## Configuración de Recursos Estáticos (`assets`)

Los recursos declarados se inyectan automáticamente en la página cuando la acción o widget correspondiente está activo:

```json
"assets": {
  "css": [
    "widget.css"
  ],
  "js": [
    "class.widget.js"
  ]
}
```
- Los archivos CSS deben ubicarse en `assets/css/`.
- Los archivos JS deben ubicarse en `assets/js/`.

---

## Configuración de Widget (`widget`)

Exclusivo para módulos de tipo widget:
```json
"widget": {
  "name": "Nombre en Lista de Widgets",
  "size": {
    "width": 12,
    "height": 6
  },
  "form_class": "WidgetForm",
  "js_class": "WidgetMiWidget",
  "use_time_selector": false,
  "refresh_rate": 60
}
```
- `size`: Dimensiones iniciales en la cuadrícula del dashboard (ancho máximo: 24, alto recomendado: 4-8).
- `form_class`: Clase PHP para el formulario modal de configuración (ubicada en `includes/WidgetForm.php`).
- `js_class`: Clase JavaScript que extiende `CWidget` (ubicada en `assets/js/`).
- `use_time_selector`: Si es `true`, el widget reacciona al selector de tiempo global del dashboard (últimos 1h, 24h, etc.).
- `refresh_rate`: Frecuencia de actualización en segundos (por defecto 60).
