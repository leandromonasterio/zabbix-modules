/* global CWidget */

/**
 * Clase JavaScript para el Widget.
 * Extiende la clase base CWidget de Zabbix.
 */
class WidgetTemplate extends CWidget {

    onInitialize() {
        super.onInitialize();
    }

    onStart() {
        super.onStart();
    }

    onActivate() {
        super.onActivate();
    }

    onDeactivate() {
        super.onDeactivate();
    }

    onDestroy() {
        super.onDestroy();
    }

    /**
     * Se ejecuta cuando la ventana o contenedor del dashboard cambia de tamaño.
     */
    onResize() {
        super.onResize();
    }

    /**
     * Se ejecuta cuando el servidor devuelve datos nuevos tras el refresco periódico.
     */
    processUpdateResponse(response) {
        super.processUpdateResponse(response);
    }
}
