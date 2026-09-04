# 07 - Referencias y Casos de Estudio del Ecosistema Zabbix

Estudiar módulos de referencia desarrollados por la comunidad y partners oficiales de Zabbix es la mejor manera de aprender patrones de arquitectura avanzados, optimizaciones de rendimiento y diseño de interfaces.

En esta guía analizamos tres proyectos destacados:
1. **[`zabbix-network-topology`](https://github.com/linuser/zabbix-network-topology)** (por Alexander Fox / PlaNet Fox): Visualización de grafos de red interactivos.
2. **[`zabbix-module-docker`](https://github.com/Monzphere/zabbix-module-docker)** (por MonZphere): Monitorización avanzada de infraestructura y microservicios con la API de Zabbix.
3. **[`monzphere-servicetree-panel`](https://github.com/Monzphere/monzphere-servicetree-panel)** (por MonZphere): Visualización jerárquica de árboles de servicios y SLA/SLO.

---

## 🗺️ Caso 1: `zabbix-network-topology` (PlaNet Fox)

- **Repositorio**: [linuser/zabbix-network-topology](https://github.com/linuser/zabbix-network-topology)
- **Autor**: Alexander Fox (`@linuser`)
- **Compatibilidad**: Zabbix 7.0 LTS / 7.4
- **Licencia**: GNU AGPL-3.0
- **Tipo**: Módulo de Frontend (`manifest_version: 2.0`)

### ¿Qué hace?
Proporciona un mapa de topología interactivo con detección automática de enlaces y estados de severidad, vista geográfica con Leaflet y simulador de fallas (*what-if failure*).

### Patrones de Arquitectura Clave:

1. **Integración limpia de librerías JS pesadas sin romper Zabbix**:
   - Utiliza **Cytoscape.js** para el cálculo de física de grafos y **Leaflet** para mapas geográficos con coordenadas del inventario del host.
   - Los assets JS y CSS se registran directamente en el `manifest.json`:
     ```json
     "assets": {
       "js": ["cytoscape.min.js", "leaflet.js", "topology.js"],
       "css": ["topology.css", "leaflet.css"]
     }
     ```
2. **Transferencia de datos PHP → JavaScript**:
   - El controlador `NetworkTopologyView` consulta los datos de hosts, interfaces y triggers vía Zabbix API interna y los inyecta en el DOM en atributos `data-*` o mediante objetos serializados en JSON listos para ser consumidos por el canvas.
3. **Simulaciones del lado del cliente (*What-if failure*)**:
   - Permite al operador hacer clic derecho en un router o switch y simular su caída; el código JavaScript recalcula las rutas en memoria y marca en rojo todos los hosts dependientes que quedan aislados, sin sobrecargar al servidor Zabbix.
4. **Persistencia granular por rol**:
   - Si un Super Administrador dibuja un enlace manual en el mapa, se persiste para todos los usuarios. Si lo hace un usuario común, se guarda como preferencia personal.

> [!TIP]
> **Cuándo usar este patrón**: Cuando necesites desarrollar módulos con diagramas de flujo, mapas geográficos de sucursales, o vistas tipo *weathermap* con tráfico en tiempo real entre enlaces.

---

## 🐳 Caso 2: `zabbix-module-docker` (MonZphere)

- **Repositorio**: [Monzphere/zabbix-module-docker](https://github.com/Monzphere/zabbix-module-docker)
- **Autor**: MonZphere (Zabbix Integration Partner)
- **Compatibilidad**: Zabbix 7.0 / 8.0
- **Licencia**: Open Source
- **Tipo**: Módulo de Frontend (`manifest_version: 2.0`)

### ¿Qué hace?
Crea una sección completa en **Monitoring → Docker** con dashboards de estado de nodos y contenedores (running, stopped), consumiendo las métricas del template oficial de *Zabbix Agent 2*.

### Patrones de Arquitectura Clave:

1. **Apalancamiento sobre plantillas oficiales existentes**:
   - En lugar de inventar un agente nuevo, se apoya en los items que ya recolecta la plantilla oficial **Docker by Zabbix agent 2**. El módulo es una capa de visualización premium sobre datos que Zabbix ya tiene.
2. **Consultas a la API con permisos del usuario autenticado**:
   - Utiliza los métodos nativos de la API de Zabbix (`API::Host()`, `API::Item()`, `API::History()`) de modo que ningún usuario pueda ver contenedores de hosts a los que no tiene acceso por grupos/roles.
3. **Sparklines SVG inline en tablas**:
   - En las columnas de uso de CPU y memoria de cada contenedor, genera gráficos de tendencia compactos en SVG directamente en el cliente, logrando una interfaz ultra rápida y moderna sin librerías externas pesadas.
4. **Ventanas modales interactivas sin recargar página**:
   - Al hacer clic en un contenedor, abre un popup modal nativo con gráficos ad-hoc de consumo histórico y detalles del contenedor.
5. **Soporte multitema profesional**:
   - Incluye hojas de estilo dedicadas (`blue-theme.css`, `dark-theme.css`) asegurando contraste perfecto tanto en el tema claro estándar como en el modo oscuro de Zabbix 7.0+.
6. **Auto-refresh seguro**:
   - Mecanismo de actualización periódica en segundo plano protegido con tokens anti-CSRF e indicador visual de frescura de datos (*last updated*).

> [!TIP]
> **Cuándo usar este patrón**: Es el modelo ideal para crear **paneles especializados de tecnologías específicas** (ej. monitoreo de Kubernetes, PostgreSQL, clusters de bases de datos, switches de red o colas de mensajería).

---

## 🌳 Caso 3: `monzphere-servicetree-panel` (MonZphere)

- **Repositorio**: [Monzphere/monzphere-servicetree-panel](https://github.com/Monzphere/monzphere-servicetree-panel)
- **Autor**: MonZphere
- **Tipo**: Panel Plugin (Grafana) con foco en Zabbix SLA & Business Services

### ¿Qué hace?
Visualiza jerarquías multinivel de servicios de negocio (Business Services) con indicadores visuales de salud, porcentaje de disponibilidad y cumplimiento de SLA/SLO.

### Lecciones e Inspiración para Módulos y Widgets Zabbix:

Aunque este proyecto está compilado para Grafana, el concepto de UX es directamente trasladable a un **Dashboard Widget nativo de Zabbix 7.0**:
1. **Modelado de Servicios de Zabbix 7.0**:
   - Zabbix 7.0 incluye un motor de servicios radicalmente mejorado (`service.get`, `sla.get`). Un widget nativo puede consultar el árbol de dependencias y calcular el impacto en cascada cuando falla un servicio hijo.
2. **Visualización de Semáforos y SLA**:
   - Presentar el estado como una tarjeta o árbol plegable con colores de severidad estándar de Zabbix (`ZBX_STYLE_PROBLEM_WARNING`, `ZBX_STYLE_PROBLEM_DISASTER`, etc.).

---

## 📊 Matriz Comparativa de Enfoques

| Característica | `lm_quick_links` (Este Repo) | `zabbix-network-topology` | `zabbix-module-docker` |
| :--- | :--- | :--- | :--- |
| **Complejidad** | Media / Inicial | Alta (Canvas + JS) | Alta (API + Modales) |
| **Enfoque principal** | Accesos operativos y navegación | Topología de red y mapas GIS | Métricas de microservicios |
| **Librerías externas** | Ninguna (100% nativo) | Cytoscape.js, Leaflet | Sparklines SVG propios |
| **Consumo de API** | No requerido | `API::Host()`, `API::Trigger()` | `API::Host()`, `API::History()` |
| **Estilos y temas** | Clases CSS nativas Zabbix | CSS adaptado a Cytoscape | CSS por tema (`dark-theme.css`) |
| **Licencia** | MIT | GNU AGPL-3.0 | Open Source |

---

## 💡 Reglas de Oro extraídas de estos proyectos

1. **No reinventar la recolección si Zabbix Agent 2 ya lo hace**: Crea módulos de UI que aprovechen métricas ya existentes en templates oficiales.
2. **Prefijar siempre selectores CSS y acciones**: Usa identificadores únicos (`monzphere_*`, `lm_*`, `nettopo_*`) para que tus estilos y rutas nunca colisionen con el núcleo de Zabbix ni con otros módulos.
3. **Respetar estrictamente los permisos**: Todo dato consultado debe pasar por las validaciones de permisos de Zabbix (`checkPermissions()` y llamadas a `API::*` con el contexto del usuario).
4. **Diseñar pensando en Dark Mode desde el día 1**: Zabbix 7.0 es utilizado predominantemente en modo oscuro en centros de operaciones (NOC/SOC).
