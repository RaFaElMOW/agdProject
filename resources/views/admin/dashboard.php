<h1>Bem-vindo, <?php echo e($user['name']); ?></h1>
<p>Este é o esqueleto do painel administrativo (Fase 0 — fundação e segurança).</p>
<ul>
  <li>Autenticação por sessão segura: ativa</li>
  <li>CSRF: ativo em todos os formulários</li>
  <li>RBAC: permissões carregadas para este usuário (<?php echo e((string) count($user['permissions'] ?? [])); ?> não exibidas aqui — ver auditoria)</li>
  <li>Auditoria: login/logout/troca de senha já registrados em <code>audit_logs</code></li>
</ul>
<p>Módulos de conteúdo, configurações e gestão de usuários chegam na Fase 1.</p>
