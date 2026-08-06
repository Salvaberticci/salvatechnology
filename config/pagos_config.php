<?php
return [
    'metodos' => [
        'pagomovil' => [
            'nombre' => 'PagoMóvil',
            'icono' => '📱',
            'descripcion' => 'Pago móvil bancario',
            'instrucciones' => [
                'Banco: Banco de Venezuela',
                'Teléfono: 0412-1731842',
                'Cédula/RIF: 30.235.360',
                'Nombre: Salvatore Berticci',
            ],
            'nota' => 'Envía el pago móvil y coloca el código de confirmación como referencia.',
        ],
        'transferencia' => [
            'nombre' => 'Transferencia Bancaria',
            'icono' => '🏦',
            'descripcion' => 'Transferencia en bolívares',
            'instrucciones' => [
                'Banco: Banco de Venezuela',
                'Cuenta: 01020747920000569415',
                'Tipo de cuenta: VES (Bolívares)',
                'Titular: Salvatore Israel Berticci Roman',
                'Cédula/RIF: V-30.236.536',
            ],
            'nota' => 'Realiza la transferencia y coloca el número de referencia.',
        ],
        'zinli' => [
            'nombre' => 'Zinli',
            'icono' => '💳',
            'descripcion' => 'Billetera digital Zinli',
            'instrucciones' => [
                'Usuario Zinli: (Configurar usuario)',
                'Nombre: (Configurar nombre)',
            ],
            'nota' => 'Envía el pago por Zinli y captura el comprobante.',
        ],
        'usdt' => [
            'nombre' => 'USDT (Binance)',
            'icono' => '₿',
            'descripcion' => 'USDT por Binance Pay o red TRC20',
            'instrucciones' => [
                'Binance ID: (Configurar ID) — para Binance Pay',
                'Wallet TRC20: (Configurar wallet) — para transferencia directa',
                'Red: TRC20 (TRON)',
            ],
            'nota' => 'Envía USDT y coloca el hash de la transacción como referencia.',
        ],
    ],
    'planes' => [
        1 => ['label' => '1 Mes',    'precio' => 40,  'meses' => 1,  'ahorro' => 0,   'desc' => 'Acceso por 30 días a clases en vivo, soporte y grupo VIP.'],
        3 => ['label' => '3 Meses',  'precio' => 110, 'meses' => 3,  'ahorro' => 10,  'desc' => 'Ideal para consolidar las bases del desarrollo con IA.'],
        6 => ['label' => '6 Meses',  'precio' => 190, 'meses' => 6,  'ahorro' => 50,  'desc' => 'El plan recomendado para estructurar tu negocio freelance con soporte continuo.'],
       12 => ['label' => '1 Año',    'precio' => 380, 'meses' => 12, 'ahorro' => 100, 'desc' => 'El pase definitivo de mentoría para vivir 100% del software asistido por IA.'],
    ],
];
