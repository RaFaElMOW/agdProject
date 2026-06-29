<?php

/**
 * Real bank/PIX/transfer data already published on donate.php, moved into the DB so the
 * 6 pages that used to duplicate it (and the donate.php accordion itself) read from one
 * place. No PayPal account is seeded here — a real PayPal business email/ID has to come
 * from the client; seeding a placeholder would silently misdirect real donations.
 */
return [
    'methods' => [
        ['national', 'bank', 'Banco Itaú', "Agência: 6375\nConta: 01630-7\nBeneficiário: Instituto Social e Comunitário Guerreiros de Deus\nCNPJ: 08.280.906/0001-19\nIBAN: BR54 6070 1190 0637 5000 0016 307C 1", 1],
        ['national', 'bank', 'Banco do Brasil', "Agência: 3327-8\nConta: 33029-9", 2],
        ['national', 'bank', 'Caixa Econômica Federal', "Agência: 0244\nConta: 000597850629-5\nTitular: Walter Alexandre Canhoni", 3],
        ['national', 'bank', 'Bradesco', "Agência: 0764\nConta: 0126319-6\nTitular: Walter Alexandre Canhoni", 4],
        ['national', 'pix', 'PIX', '08.280.906/0001-19', 5],
        ['national', 'zelle', 'Zelle', "mgiovanac@hotmail.com\nalexandregiovana@uol.com.br", 6],
        ['international', 'bank', 'Ecobank Niger (Niamey)', "SWIFT: ECOCNENIXXX\nIBAN: NE09 5010 0616 0050 0030 0167\nConta: Association Guerreiros de Deus", 1],
        ['international', 'western_union', 'Western Union', 'Envie um e-mail para receber os detalhes de envio.', 2],
        ['international', 'wise', 'Wise', '@walter58c', 3],
    ],

    'preset_amounts' => [
        ['BRL', 30],
        ['BRL', 60],
        ['BRL', 100],
        ['BRL', 200],
        ['USD', 10],
        ['USD', 25],
        ['USD', 50],
        ['USD', 100],
    ],
];
