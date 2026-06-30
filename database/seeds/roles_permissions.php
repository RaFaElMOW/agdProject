<?php

/**
 * Catalogue of roles/permissions for the admin panel. Returned as plain data so the
 * seeder can upsert idempotently; modules built in later phases just need their
 * slug to already exist here (or get added alongside the module's migration).
 */
return [
    'permissions' => [
        ['slug' => 'users.manage', 'name' => 'Gerenciar usuários', 'module' => 'users'],
        ['slug' => 'roles.manage', 'name' => 'Gerenciar perfis e permissões', 'module' => 'users'],
        ['slug' => 'settings.manage', 'name' => 'Gerenciar configurações globais', 'module' => 'settings'],
        ['slug' => 'content.manage', 'name' => 'Gerenciar conteúdo institucional (Sobre/Quem Somos)', 'module' => 'content'],
        ['slug' => 'team.manage', 'name' => 'Gerenciar equipe', 'module' => 'team'],
        ['slug' => 'testimonials.manage', 'name' => 'Gerenciar depoimentos', 'module' => 'testimonials'],
        ['slug' => 'projects.manage', 'name' => 'Gerenciar projetos', 'module' => 'projects'],
        ['slug' => 'sponsorship.manage', 'name' => 'Gerenciar apadrinhamento', 'module' => 'sponsorship'],
        ['slug' => 'books.manage', 'name' => 'Gerenciar livros', 'module' => 'books'],
        ['slug' => 'media.manage', 'name' => 'Gerenciar mídia', 'module' => 'media'],
        ['slug' => 'blog.manage', 'name' => 'Gerenciar posts do blog', 'module' => 'blog'],
        ['slug' => 'blog.publish', 'name' => 'Publicar/agendar posts do blog', 'module' => 'blog'],
        ['slug' => 'comments.moderate', 'name' => 'Moderar comentários', 'module' => 'blog'],
        ['slug' => 'donations.manage', 'name' => 'Gerenciar métodos de doação', 'module' => 'donations'],
        ['slug' => 'contact.view', 'name' => 'Visualizar mensagens de contato', 'module' => 'contact'],
        ['slug' => 'audit.view', 'name' => 'Visualizar logs de auditoria', 'module' => 'audit'],
        ['slug' => 'translations.manage', 'name' => 'Gerenciar traduções', 'module' => 'translations'],
        ['slug' => 'security.manage', 'name' => 'Gerenciar configurações de segurança', 'module' => 'security'],
    ],

    'roles' => [
        ['slug' => 'administrator', 'name' => 'Administrator', 'permissions' => '*'],
        ['slug' => 'editor', 'name' => 'Editor', 'permissions' => [
            'content.manage', 'team.manage', 'testimonials.manage', 'projects.manage',
            'sponsorship.manage', 'books.manage', 'media.manage', 'blog.manage', 'blog.publish',
            'translations.manage',
        ]],
        ['slug' => 'moderador', 'name' => 'Moderador', 'permissions' => [
            'comments.moderate', 'testimonials.manage', 'contact.view',
        ]],
        ['slug' => 'visualizador', 'name' => 'Visualizador', 'permissions' => []],
    ],
];
