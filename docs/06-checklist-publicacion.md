# 06 - Lista de Comprobación y Buenas Prácticas (Estándar initMAX / Oficial)

Antes de publicar o desplegar cualquier módulo en un servidor de Zabbix en producción, sigue esta lista de verificación para asegurar que cumple con los más altos estándares de calidad, seguridad y mantenibilidad.

---

## 1. Identificación y Metadatos (`manifest.json`)

- [ ] **Versión de manifiesto**: `manifest_version` es exactamente `2.0`.
- [ ] **Prefijo de ID**: El `id` tiene un prefijo identificatorio (ej. `lm_` o `andreani_`) y no contiene caracteres especiales ni mayúsculas.
- [ ] **Espacio de nombres**: El `namespace` es único, en formato PascalCase y sin guiones (ej. `LmQuickLinks`).
- [ ] **Versión semántica**: El campo `version` sigue el formato `X.Y.Z` (ej. `1.0.0`).
- [ ] **Acciones declaradas**: Todas las acciones referenciadas en el código existen y están correctamente mapeadas a su clase y vista en `actions`.
- [ ] **Recursos declarados**: Todos los archivos de `assets/css/` y `assets/js/` que se usen están listados en la clave `assets`.

---

## 2. Seguridad y Permisos

- [ ] **Validación de Entradas**: Todos los parámetros leídos por el controlador pasan por `checkInput()` con validaciones de tipo (`int32`, `string`, `not_empty`, etc.).
- [ ] **Control de Roles**: El método `checkPermissions()` valida explícitamente el rol mínimo requerido o comprueba con `checkAccess(CRoleHelper::...)`.
- [ ] **CSRF Control**: Las acciones de modificación (POST/cambios en DB) mantienen activa la validación CSRF y los formularios contienen el token `CCsrfTokenHelper`. Las vistas informativas puras desactivan CSRF en `init()` para evitar falsos positivos.
- [ ] **Escape de Datos**: Todo dato renderizado en HTML pasa por las clases de Zabbix (`CDiv`, `CTableInfo`, etc.) o la función de escape `CHtml::encode()` para prevenir vulnerabilidades XSS.

---

## 3. Internacionalización y UI

- [ ] **Textos Traducibles**: Todo texto visible al usuario está envuelto en la función `_('Texto')`.
- [ ] **Uso de Clases Nativas**: La interfaz usa componentes de Zabbix (`CHtmlPage`, `CTableInfo`, `CButton`, etc.) en lugar de HTML plano rígido.
- [ ] **Compatibilidad de Temas**: No se usan colores hexadecimales fijos en elementos críticos; se aprovechan las clases `ZBX_STYLE_*` y variables CSS nativas para soportar modo claro y oscuro sin romperse.
- [ ] **CSS Scoped**: En widgets, los estilos están encapsulados con `div.dashboard-widget-<id>`.

---

## 4. Estructura y Empaquetado

- [ ] **README descriptivo**: El módulo incluye su propio `README.md` con descripción, capturas o ejemplos, compatibilidad y pasos de instalación.
- [ ] **Limpieza de archivos basura**: No hay archivos de backup (`.bak`, `.swp`), carpetas del sistema operativo (`Thumbs.db`, `.DS_Store`) ni archivos de depuración en la raíz del módulo.
- [ ] **Empaquetado**: El paquete `.zip` contiene la carpeta raíz del módulo y dentro de ella el `manifest.json`.
