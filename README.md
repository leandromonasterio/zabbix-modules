<div align="center">

# Zabbix UI Modules & Dashboard Widgets

<p>
desarrollado y mantenido por <strong>Leandro Monasterio</strong>
</p>

<p><strong>Suite de módulos frontend y widgets de dashboard para Zabbix 7.0 LTS y 7.4.</strong><br>
Construidos siguiendo las directrices oficiales de Zabbix y las mejores prácticas de la comunidad (estándar initMAX).</p>

<p>
  <a href="https://www.zabbix.com/"><img src="https://img.shields.io/badge/Zabbix-7.0%20LTS%20%7C%207.4-d30200.svg" alt="Zabbix 7.0 | 7.4"></a>
  <img src="https://img.shields.io/badge/Manifest-v2.0-blue.svg" alt="Manifest 2.0">
  <img src="https://img.shields.io/badge/PHP-8.0%2B-777bb4.svg" alt="PHP 8.0+">
  <a href="./LICENSE"><img src="https://img.shields.io/badge/Licencia-MIT-green.svg" alt="Licencia MIT"></a>
  <img src="https://img.shields.io/badge/Dark%20Mode-Ready-black.svg" alt="Dark Mode Ready">
</p>

<p>
  <a href="#catálogo-de-módulos"><strong>Catálogo</strong></a> &nbsp;·&nbsp;
  <a href="#instalación-rápida"><strong>Instalación</strong></a> &nbsp;·&nbsp;
  <a href="#desarrollo-de-nuevos-módulos"><strong>Desarrollo</strong></a> &nbsp;·&nbsp;
  <a href="#documentación-y-guías"><strong>Guías</strong></a> &nbsp;·&nbsp;
  <a href="https://github.com/leandromonasterio/zabbix-modules"><strong>GitHub</strong></a>
</p>

</div>

---

## 🌟 Visión del Proyecto

Este repositorio centraliza el desarrollo, mantenimiento y distribución de módulos personalizados para la interfaz web de Zabbix. Todos los módulos cumplen con:

- **Especificación Oficial `manifest_version: 2.0`**: Compatible nativamente con **Zabbix 7.0 LTS**, **Zabbix 7.4** y Zabbix 6.4+.
- **Seguridad Integrada**: Validación exhaustiva de parámetros de entrada (`checkInput`), control de roles (`checkPermissions`) y protección anti-CSRF.
- **Componentes Nativos Zabbix**: Vistas diseñadas con las clases PHP del framework de Zabbix (`CHtmlPage`, `CTableInfo`, `CDiv`), garantizando soporte perfecto para temas oscuros y claros.
- **Scaffolding y Empaquetado Automatizado**: Scripts en PowerShell para crear y empaquetar módulos con un solo clic.

---

## 📦 Catálogo de Módulos

| Módulo | Tipo | Versión | Compatibilidad | Descripción |
| :--- | :---: | :---: | :---: | :--- |
| [`lm_quick_links`](./modules/lm_quick_links/) | Módulo UI | `1.0.0` | Zabbix 7.0 / 7.4 | Hub centralizado de accesos directos, wikis y herramientas operativas en el menú de Monitoreo. |

*(Nuevos módulos y widgets en desarrollo)*

---

## 🚀 Instalación Rápida

### Opción A: Copia Directa del Código
1. Clona el repositorio o descarga la carpeta del módulo deseado:
   ```bash
   git clone https://github.com/leandromonasterio/zabbix-modules.git
   ```
2. Copia la carpeta del módulo en la ruta de módulos de tu frontend de Zabbix:
   - **Debian / Ubuntu**: `/usr/share/zabbix/modules/`
   - **RHEL / Rocky / AlmaLinux**: `/usr/share/zabbix/ui/modules/`
   ```bash
   sudo cp -r zabbix-modules/modules/lm_quick_links /usr/share/zabbix/modules/
   sudo chown -R www-data:www-data /usr/share/zabbix/modules/lm_quick_links
   ```
3. En Zabbix Web, ve a **Administration → General → Modules** (o *Administración → Módulos*).
4. Haz clic en **Scan directory** (*Escanear directorio*).
5. Localiza el módulo y haz clic en su estado para cambiarlo a **Enabled**.

### Opción B: Mediante Paquete ZIP
1. Genera el `.zip` con la herramienta de empaquetado:
   ```powershell
   pwsh tools/Package-Module.ps1 -ModuleId lm_quick_links
   ```
2. Descomprime el archivo resultante dentro del directorio `modules/` de Zabbix y habilítalo en la interfaz web.

---

## 🛠️ Desarrollo de Nuevos Módulos

El repositorio incluye plantillas preconfiguradas y un asistente automatizado en PowerShell para crear módulos o widgets en segundos, sin necesidad de escribir PHP desde cero:

### 1. Crear un Módulo de Frontend (Menús y Páginas)
```powershell
pwsh tools/New-ZabbixModule.ps1 `
    -Type module `
    -Id lm_panel_guardia `
    -Name "Panel de Guardia" `
    -Description "Gestión de turnos y guardias operativas"
```

### 2. Crear un Widget para Dashboard
```powershell
pwsh tools/New-ZabbixModule.ps1 `
    -Type widget `
    -Id lm_reloj_red `
    -Name "Reloj de Red" `
    -Description "Reloj en tiempo real sincronizado para NOC"
```

El asistente configurará automáticamente:
- Estructura completa de carpetas (`actions/`, `views/`, `includes/`, `assets/`).
- `manifest.json` v2.0 con namespaces y acciones enlazadas.
- Clases de controlador con validación de entradas y permisos.
- Vistas con componentes visuales nativos de Zabbix.
- Documentación `README.md` individual para el módulo.

### 3. Validar y Empaquetar
```powershell
pwsh tools/Package-Module.ps1 -ModuleId lm_panel_guardia
```
El script valida la sintaxis JSON, revisa los campos requeridos por Zabbix y genera el archivo comprimido en la carpeta `dist/`.

---

## 📚 Documentación y Guías de Buenas Prácticas

En el directorio [`docs/`](./docs/) encontrarás guías exhaustivas paso a paso:

- [**01 - Anatomía de `manifest.json`**](./docs/01-anatomia-manifest.md): Explicación de cada campo, reglas de IDs y mapeo de rutas.
- [**02 - Ciclo de Vida y Manipulación de Menús**](./docs/02-ciclo-de-vida-y-menus.md): Eventos `init()`, `onBeforeAction()` e inyección en el menú principal.
- [**03 - Controladores, Permisos y CSRF**](./docs/03-controladores-seguridad.md): `CController`, validación estricta de inputs y tokens CSRF.
- [**04 - Desarrollo de Widgets para Dashboard**](./docs/04-desarrollo-widgets.md): `WidgetForm`, `WidgetView`, ciclo de vida en JS con `CWidget` y estilos scoped.
- [**05 - Catálogo de Componentes UI en PHP**](./docs/05-componentes-ui-zabbix.md): Uso de `CHtmlPage`, `CTableInfo`, `CFormGrid`, badges y botones oficiales.
- [**06 - Lista de Comprobación para Publicación**](./docs/06-checklist-publicacion.md): Checklist de calidad y seguridad antes de desplegar en producción.
- [**07 - Referencias y Casos de Estudio del Ecosistema**](./docs/07-referencias-y-casos-de-estudio.md): Análisis de arquitectura de proyectos destacados (`zabbix-network-topology`, `zabbix-module-docker`, MonZphere).


---

## 📁 Estructura del Repositorio

```text
zabbix-modules/
├── docs/                   # Guías completas de arquitectura y desarrollo
├── modules/                # Módulos y widgets listos para producción
│   └── lm_quick_links/     # Módulo de accesos rápidos de ejemplo
├── templates/              # Plantillas base (Boilerplates)
│   ├── frontend-module/    # Plantilla para módulos con menús/páginas
│   └── dashboard-widget/   # Plantilla para widgets de dashboard
├── tools/                  # Scripts de automatización en PowerShell
│   ├── New-ZabbixModule.ps1
│   └── Package-Module.ps1
├── .editorconfig           # Estándares de indentación oficiales Zabbix
├── .gitignore              # Exclusiones de Git
├── LICENSE                 # Licencia MIT
└── README.md               # Portada principal del repositorio
```

---

## 📄 Licencia

Este repositorio se distribuye bajo la licencia [MIT](./LICENSE).
