<div align="center">

# Quick Links Hub for Zabbix

<p>
desarrollado por <strong>Leandro Monasterio</strong>
</p>

<p><strong>Acceso centralizado a recursos operativos, documentación y herramientas directamente desde el menú de Zabbix.</strong><br>
Evita que el equipo de soporte u operaciones pierda tiempo buscando URLs de wikis, dashboards auxiliares o contactos de guardia.</p>

<p>
  <img src="https://img.shields.io/badge/Zabbix-7.0%20LTS%20%7C%207.4-d30200.svg" alt="Zabbix 7.0 | 7.4">
  <img src="https://img.shields.io/badge/Versi%C3%B3n-1.0.0-blue.svg" alt="Versión 1.0.0">
  <img src="https://img.shields.io/badge/PHP-8.0%2B-777bb4.svg" alt="PHP 8.0+">
  <img src="https://img.shields.io/badge/Licencia-MIT-green.svg" alt="Licencia MIT">
</p>

</div>

---

## Características

- 🚀 **Integración Nativa**: Se incorpora directamente en el submenú de *Monitoring* (Monitorización).
- 🏷️ **Filtro por Categorías**: Clasificación dinámica por Documentación, Herramientas o Monitoreo.
- 🎨 **Soporte Completo de Temas**: Totalmente compatible con Dark Mode, Light Mode y Alto Contraste de Zabbix.
- 🛡️ **Seguro**: Control de permisos integrado y cumplimiento estricto de estándares Zabbix Frontend.

## Requisitos

| Componente | Requisito Mínimo | Recomendado |
| :--- | :--- | :--- |
| **Zabbix Frontend** | 6.4 | 7.0 LTS / 7.4 |
| **PHP** | 7.4 | 8.0 o superior |
| **Permisos** | Usuario autenticado en frontend |

## Instalación

### Método 1: Despliegue Directo (Recomendado)

1. Descarga o clona la carpeta `lm_quick_links` en el directorio de módulos de tu frontend de Zabbix:
   ```bash
   # En Debian/Ubuntu:
   sudo cp -r lm_quick_links /usr/share/zabbix/modules/

   # En RHEL/CentOS/AlmaLinux:
   sudo cp -r lm_quick_links /usr/share/zabbix/ui/modules/
   ```

2. Ajusta los permisos para el servidor web:
   ```bash
   sudo chown -R www-data:www-data /usr/share/zabbix/modules/lm_quick_links
   # O en RHEL (nginx / apache):
   sudo chown -R nginx:nginx /usr/share/zabbix/ui/modules/lm_quick_links
   ```

3. Abre Zabbix en tu navegador y ve a **Administration → General → Modules** (o *Administración → Módulos*).
4. Pulsa el botón **Scan directory** (*Escanear directorio*).
5. En la lista, localiza **Quick Links Hub** y haz clic sobre el estado **Disabled** para pasarlo a **Enabled**.

### Método 2: Paquete ZIP

Puedes generar el archivo comprimido listo para subir o distribuir usando el script:
```powershell
pwsh tools/Package-Module.ps1 -ModuleId "lm_quick_links"
```
Descomprime el archivo `.zip` resultante dentro de tu directorio `modules/` de Zabbix.

## Configuración y Personalización

Para agregar o modificar enlaces predeterminados, edita el array `$all_links` dentro del archivo:
`actions/QuickLinksView.php`

Cada elemento soporta los siguientes campos:
```php
[
    'name'        => 'Nombre del Servicio',
    'description' => 'Descripción breve del servicio.',
    'url'         => 'https://mi-servicio.empresa.com',
    'category'    => 'monitoring', // 'monitoring', 'tools' o 'docs'
    'badge'       => 'Interno',
    'badge_style' => ZBX_STYLE_GREEN
]
```

## Licencia

Este módulo se distribuye bajo la licencia [MIT](../../LICENSE).
