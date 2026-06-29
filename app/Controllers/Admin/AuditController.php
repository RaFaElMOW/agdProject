<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\AuditLogRepository;

class AuditController
{
    private const PER_PAGE = 50;

    public function index(Request $request): void
    {
        $page = max(1, (int) $request->input('page', 1));
        $result = (new AuditLogRepository())->paginated($page, self::PER_PAGE);

        View::output('admin/audit', [
            'logs' => $result['rows'],
            'page' => $page,
            'totalPages' => (int) ceil($result['total'] / self::PER_PAGE) ?: 1,
        ], 'admin/layout');
    }
}
