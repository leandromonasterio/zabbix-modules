# {{MODULE_NAME}} (Dashboard Widget)

{{MODULE_DESCRIPTION}}

## Compatibilidad

- **Zabbix**: 7.0 LTS / 7.4 (compatible con 6.4+)
- **PHP**: 8.0+

## Instalación

1. Copia o enlaza esta carpeta en el directorio de módulos de Zabbix:
   ```bash
   cp -r {{MODULE_ID}} /usr/share/zabbix/modules/
   ```
2. En la interfaz web de Zabbix, ve a **Administración → Módulos** (*Administration → General → Modules*).
3. Haz clic en **Escanear directorio** (*Scan directory*).
4. Busca **{{MODULE_NAME}}** y haz clic en el estado para cambiarlo a **Habilitado** (*Enabled*).
5. Abre cualquier **Dashboard**, haz clic en **Editar dashboard → Añadir widget**, y selecciona **{{MODULE_NAME}}**.
