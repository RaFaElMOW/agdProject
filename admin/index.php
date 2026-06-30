<?php

declare(strict_types=1);

// This file is intentionally inert. The admin panel is served exclusively
// through the token-gated entry point at /portal/{admin_route_token}/...
// (see portal/index.php and app/Security/RouteManager.php). Common admin
// paths must never reveal that a panel exists, so this always 404s — the
// same response an attacker gets for any nonexistent path.
http_response_code(404);
echo 'Não encontrado.';
