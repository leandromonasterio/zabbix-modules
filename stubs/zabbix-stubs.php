<?php
/**
 * Zabbix 7.0 LTS / 7.4 PHP Core IDE Stubs
 * 
 * Este archivo provee definiciones de tipos, clases y constantes del núcleo de Zabbix
 * para que el editor de código (IDE) ofrezca autocompletado y elimine advertencias
 * de "clases desconocidas" durante el desarrollo de módulos independientes.
 * 
 * No debe incluirse en producción; sólo se utiliza en tiempo de desarrollo.
 */

namespace {

    // Constantes de tipos de usuario
    if (!defined('USER_TYPE_ZABBIX_USER')) {
        define('USER_TYPE_ZABBIX_USER', 1);
        define('USER_TYPE_ZABBIX_ADMIN', 2);
        define('USER_TYPE_SUPER_ADMIN', 3);
    }

    // Constantes de estilos visuales nativos
    if (!defined('ZBX_STYLE_GREEN')) {
        define('ZBX_STYLE_GREEN', 'green');
        define('ZBX_STYLE_RED', 'red');
        define('ZBX_STYLE_BLUE', 'blue');
        define('ZBX_STYLE_ORANGE', 'orange');
        define('ZBX_STYLE_GREY', 'grey');
        define('ZBX_STYLE_BTN_LINK', 'btn-link');
        define('ZBX_STYLE_BTN_ALT', 'btn-alt');
        define('ZBX_TEXTAREA_STANDARD_WIDTH', '300px');
    }

    // Función global de traducción GetText de Zabbix
    if (!function_exists('_')) {
        function _(string $message): string {
            return $message;
        }
    }

    // Controlador base de Zabbix
    class CController {
        protected array $fields_values = [];

        public function init(): void {}
        public function getAction(): string { return ''; }
        protected function disableCsrfValidation(): void {}
        protected function validateInput(array $fields): bool { return true; }
        protected function setResponse($response): void {}
        protected function getUserType(): int { return 1; }
        protected function getInput(string $name, $default = null) { return $default; }
        protected function getDebugMode(): int { return 0; }
        protected function checkAccess(string $rule): bool { return true; }
    }

    // Controlador base para vistas de widgets
    class CControllerDashboardWidgetView extends CController {
        public $widget;
    }

    // Respuestas de controladores
    class CControllerResponseData {
        public function __construct(array $data = []) {}
    }

    class CControllerResponseFatal {}

    // Contenedor principal de la aplicación Zabbix
    class APP {
        public static function Component(): self {
            return new self();
        }
        public function get(string $component) {
            return new CMenuItem();
        }
    }

    // Clases del sistema de menús
    class CMenuItem {
        public function __construct(string $name = '') {}
        public function setAction(string $action): self { return $this; }
        public function setSubMenu($submenu): self { return $this; }
        public function setIcon(string $icon): self { return $this; }
        public function getSubmenu(): self { return $this; }
        public function add($item): self { return $this; }
        public function findOrAdd(string $name): self { return $this; }
        public function insertAfter(string $after, $item): self { return $this; }
    }

    class CMenu {
        public function __construct(array $items = []) {}
    }

    // Componentes de interfaz gráfica (HTML)
    class CHtmlPage {
        public function setTitle(string $title): self { return $this; }
        public function setTitleSubmenu(array $submenu): self { return $this; }
        public function setControls($controls): self { return $this; }
        public function addItem($item): self { return $this; }
        public function show(): void {}
    }

    class CDiv {
        public function __construct($items = null) {}
        public function addClass(string $class): self { return $this; }
        public function addItem($item): self { return $this; }
        public function show(): void {}
    }

    class CSpan {
        public function __construct(string $text = '') {}
        public function addClass(string $class): self { return $this; }
    }

    class CTableInfo {
        public function setHeader(array $header): self { return $this; }
        public function setNoDataMessage(string $msg): self { return $this; }
        public function addRow(array $row): self { return $this; }
    }

    class CCol {
        public function __construct($item = null) {}
    }

    class CLink {
        public function __construct(string $caption, string $url = '') {}
        public function setTarget(string $target): self { return $this; }
        public function setAttribute(string $name, string $val): self { return $this; }
        public function addClass(string $class): self { return $this; }
    }

    class CUrl {
        public function __construct(string $url = '') {}
        public function setArgument(string $key, $value): self { return $this; }
        public function getUrl(): string { return ''; }
    }

    class CTag {
        public function __construct(string $tag, bool $paired = true, $body = null) {}
        public function addClass(string $class): self { return $this; }
        public function setAttribute(string $name, string $val): self { return $this; }
    }

    class CList {
        public function addClass(string $class): self { return $this; }
        public function addItem($item): self { return $this; }
    }

    class CButton {
        public function __construct(string $name, string $caption = '') {}
        public function addClass(string $class): self { return $this; }
    }

    class CRoleHelper {
        const UI_MONITORING_HOSTS = 'ui.monitoring.hosts';
        const UI_ADMINISTRATION_GENERAL = 'ui.administration.general';
    }
}

namespace Zabbix\Core {
    class CModule {
        public function init(): void {}
        public function onBeforeAction(\CController $action): void {}
        public function onTerminate(\CController $action): void {}
    }
}

namespace Zabbix\Widgets {
    class CWidgetForm {
        public function addFields(): self { return $this; }
        public function addField($field): self { return $this; }
    }
}

namespace Zabbix\Widgets\Fields {
    class CWidgetFieldTextBox {
        public function __construct(string $name, string $label = '') {}
        public function setDefault($val): self { return $this; }
    }

    class CWidgetFieldColor {
        public function __construct(string $name, string $label = '') {}
        public function setDefault($val): self { return $this; }
    }
}
