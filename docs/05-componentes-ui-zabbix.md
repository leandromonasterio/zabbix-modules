# 05 - Catálogo de Componentes UI de Zabbix en PHP

Zabbix posee una biblioteca completa de clases PHP orientadas a objetos para renderizar elementos HTML consistentes con el diseño nativo del frontend (temas claros, oscuros y de alto contraste).

En lugar de concatenar cadenas HTML manualmente, **siempre** es una buena práctica utilizar los componentes oficiales de Zabbix.

---

## 1. Página Completa (`CHtmlPage`)

Es el contenedor principal de cualquier vista de módulo que use `"layout": "layout.htmlpage"`.

```php
<?php

$page = (new CHtmlPage())
    ->setTitle(_('Panel de Enlaces Operativos'))
    ->setTitleSubmenu([
        'main_section' => [
            'title' => _('Operaciones')
        ]
    ]);

// Añadir botones de acción en la cabecera superior derecha
$page->setControls(
    (new CTag('nav', true,
        (new CList())
            ->addItem(new CSubmit('export', _('Exportar a CSV')))
            ->addItem((new CButton('create', _('Nuevo Enlace')))->addClass(ZBX_STYLE_BTN_ALT))
    ))->setAttribute('aria-label', _('Content controls'))
);

// Añadir el contenido
$page->addItem($mi_contenido);

$page->show();
```

---

## 2. Tablas de Información (`CTableInfo`)

Renderiza tablas con el estilo oficial de Zabbix, con cabeceras ordenables, filas cebra y soporte automático para mensajes cuando no hay datos.

```php
<?php

$table = (new CTableInfo())
    ->setHeader([
        _('Nombre del Sistema'),
        _('Dirección / URL'),
        _('Estado'),
        _('Acciones')
    ])
    ->setNoDataMessage(_('No se encontraron enlaces configurados.'));

foreach ($data['items'] as $item) {
    $status_badge = $item['active']
        ? (new CSpan(_('Activo')))->addClass(ZBX_STYLE_GREEN)
        : (new CSpan(_('Inactivo')))->addClass(ZBX_STYLE_RED);

    $link = (new CLink($item['url'], $item['url']))
        ->setTarget('_blank')
        ->setAttribute('rel', 'noopener noreferrer');

    $table->addRow([
        new CCol(bold($item['name'])),
        $link,
        $status_badge,
        (new CButton('edit', _('Editar')))->addClass(ZBX_STYLE_BTN_LINK)
    ]);
}

$page->addItem($table);
```

---

## 3. Contenedores y Cuadrículas (`CDiv`, `CFormGrid`)

Para diseñar formularios y cajas con el espaciado y alineación nativos:

```php
<?php

$form_grid = (new CFormGrid())
    ->addItem([
        (new CLabel(_('Nombre de la Guardia'), 'guard_name'))->setAsteriskMark(),
        new CFormField((new CTextBox('guard_name', ''))->setWidth(ZBX_TEXTAREA_STANDARD_WIDTH))
    ])
    ->addItem([
        new CLabel(_('Teléfono / Escalado'), 'phone'),
        new CFormField((new CTextBox('phone', ''))->setWidth(ZBX_TEXTAREA_STANDARD_WIDTH))
    ])
    ->addItem([
        new CLabel(_('Notas de Turno'), 'notes'),
        new CFormField((new CTextArea('notes', ''))->setWidth(ZBX_TEXTAREA_STANDARD_WIDTH))
    ]);

$page->addItem($form_grid);
```

---

## 4. Mensajes de Notificación y Alerta

Zabbix provee helpers para mostrar banners informativos, advertencias o errores:

```php
<?php

// Mensaje de éxito
CMessageHelper::addSuccess(_('Los cambios fueron guardados exitosamente.'));

// O generar cajas visuales directas:
$warning_box = (new CWarningBox())
    ->setTitle(_('Atención'))
    ->addMessage(_('Este módulo requiere conectividad directa con la API externa.'));

$page->addItem($warning_box);
```

---

## 5. Constantes de Estilo Nativas (`ZBX_STYLE_*`)

Para asegurar compatibilidad con todos los temas visuales de Zabbix (Dark, Light, High Contrast):

| Constante | Uso habitual |
| :--- | :--- |
| `ZBX_STYLE_GREEN` | Textos/badges de éxito o estado OK |
| `ZBX_STYLE_RED` | Textos/badges de alerta crítica o error |
| `ZBX_STYLE_ORANGE` | Advertencias |
| `ZBX_STYLE_GREY` | Elementos deshabilitados o secundarios |
| `ZBX_STYLE_BTN_ALT` | Botón secundario estilizado |
| `ZBX_STYLE_BTN_LINK` | Botón con aspecto de enlace |
| `ZBX_STYLE_NOWRAP` | Evita que el texto de la celda salte de línea |
