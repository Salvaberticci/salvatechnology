<?php
return [
    'metodos' => [
        'pagomovil' => [
            'nombre' => 'PagoMóvil',
            'icono' => '📱',
            'descripcion' => 'Pago móvil bancario',
            'instrucciones' => [
                'Banco: (Configurar banco)',
                'Teléfono: (Configurar teléfono)',
                'Cédula/RIF: (Configurar cédula)',
                'Nombre: (Configurar nombre)',
            ],
            'nota' => 'Envía el pago móvil y coloca el código de confirmación como referencia.',
        ],
        'transferencia' => [
            'nombre' => 'Transferencia Bancaria',
            'icono' => '🏦',
            'descripcion' => 'Transferencia en bolívares',
            'instrucciones' => [
                'Banco: (Configurar banco)',
                'Cuenta: (Configurar número de cuenta)',
                'Tipo de cuenta: (Corriente/Ahorro)',
                'Titular: (Configurar nombre)',
                'Cédula/RIF: (Configurar cédula)',
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
];
