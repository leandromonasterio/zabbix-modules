<?php

use CDiv;
use CSpan;

/**
 * Vista HTML del widget.
 */

(new CDiv([
    (new CDiv($data['custom_message']))->addClass('widget-badge-text'),
    (new CDiv([
        new CSpan(_('Última sincronización: ')),
        (new CSpan(date('H:i:s', $data['timestamp'])))->addClass('widget-time')
    ]))->addClass('widget-footer-info')
]))
    ->addClass('widget-container-inner')
    ->show();
