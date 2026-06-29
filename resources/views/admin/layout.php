<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Painel Administrativo - AGD Niger</title>
<style>
  * { box-sizing: border-box; }
  body { font-family: -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 0; background: #f4f5f7; color: #1f2630; }
  header { background: #1f2630; color: #fff; padding: .9rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
  header .brand { font-weight: 700; }
  header form { margin: 0 0 0 .5rem; display:inline; }
  header button { background: transparent; border: 1px solid #ffffff55; color: #fff; padding: .4rem .8rem; border-radius: 4px; cursor: pointer; font-size: .85rem; }
  header button:hover { background: #ffffff22; }
  .layout { display: flex; min-height: calc(100vh - 56px); }
  nav.sidebar { width: 220px; background: #fff; border-right: 1px solid #e3e5e8; padding: 1.2rem 0; flex-shrink: 0; }
  nav.sidebar a { display: block; padding: .55rem 1.2rem; color: #444; text-decoration: none; font-size: .92rem; }
  nav.sidebar a:hover { background: #f4f5f7; }
  nav.sidebar a.active { background: #fff1e8; color: #f96d00; font-weight: 600; border-right: 3px solid #f96d00; }
  main { padding: 1.8rem; flex: 1; max-width: 1000px; }
  .panel { background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
  .error { background:#fdecea; color:#a8201a; padding:.6rem .8rem; border-radius:4px; margin-bottom:1rem; font-size:.9rem; }
  .success { background:#e9f7ef; color:#1e7e44; padding:.6rem .8rem; border-radius:4px; margin-bottom:1rem; font-size:.9rem; }
  fieldset { border: 1px solid #e3e5e8; border-radius: 6px; margin-bottom: 1.2rem; padding: 1rem 1.2rem; }
  legend { font-weight: 600; font-size: .9rem; color: #555; padding: 0 .4rem; }
  .field { margin-bottom: 1rem; }
  label { display:block; font-size:.85rem; margin-bottom:.3rem; color:#555; }
  input[type=text], input[type=email], input[type=password], input[type=url], input[type=tel], input[type=search], textarea, select {
    padding:.55rem .7rem; border:1px solid #ccc; border-radius:4px; box-sizing:border-box; width: 100%; max-width: 480px; display:block; font-size: .92rem;
  }
  input[type=file] { display:block; margin-bottom: .3rem; }
  textarea { max-width: 100%; min-height: 90px; font-family: inherit; }
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 1.5rem; }
  button[type=submit], .btn { padding:.55rem 1.1rem; background:#f96d00; border:none; border-radius:4px; color:#fff; font-weight:600; cursor:pointer; font-size: .9rem; text-decoration:none; display:inline-block; }
  .btn-secondary { background: #6c757d; }
  .btn-danger { background: #c0392b; }
  .btn-sm { padding: .3rem .7rem; font-size: .8rem; }
  table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
  table th, table td { text-align: left; padding: .55rem .6rem; border-bottom: 1px solid #eee; font-size: .88rem; }
  table th { color: #777; font-weight: 600; font-size: .8rem; text-transform: uppercase; }
  .badge { display:inline-block; padding: .15rem .5rem; border-radius: 3px; font-size: .75rem; font-weight: 600; }
  .badge-active { background:#e9f7ef; color:#1e7e44; }
  .badge-inactive { background:#f4f5f7; color:#777; }
  .badge-blocked { background:#fdecea; color:#a8201a; }
  .actions form { display:inline; }
  .hint { color: #888; font-size: .8rem; margin-top: -.5rem; margin-bottom: .8rem; }
  h1 { font-size: 1.3rem; margin-top: 0; }
</style>
</head>
<body>
<header>
  <span class="brand">AGD Niger — Painel</span>
  <span>
    <form method="post" action="<?php echo e(admin_url('/admin/logout-global')); ?>">
      <?php echo csrf_field(); ?>
      <button type="submit" onclick="return confirm('Encerrar todas as sessões deste usuário?');">Sair de todos os dispositivos</button>
    </form>
    <form method="post" action="<?php echo e(admin_url('/admin/logout')); ?>">
      <?php echo csrf_field(); ?>
      <button type="submit">Sair</button>
    </form>
  </span>
</header>
<div class="layout">
<nav class="sidebar">
  <a href="<?php echo e(admin_url('/admin')); ?>"<?php echo nav_active('/admin', true); ?>>Dashboard</a>
  <a href="<?php echo e(admin_url('/admin/configuracoes')); ?>"<?php echo nav_active('/admin/configuracoes'); ?>>Configurações</a>
  <a href="<?php echo e(admin_url('/admin/menus')); ?>"<?php echo nav_active('/admin/menus'); ?>>Menus</a>
  <a href="<?php echo e(admin_url('/admin/conteudo/sobre')); ?>"<?php echo nav_active('/admin/conteudo/sobre'); ?>>Sobre Nós</a>
  <a href="<?php echo e(admin_url('/admin/conteudo/quemsomos')); ?>"<?php echo nav_active('/admin/conteudo/quemsomos'); ?>>Quem Somos</a>
  <a href="<?php echo e(admin_url('/admin/equipe')); ?>"<?php echo nav_active('/admin/equipe'); ?>>Equipe</a>
  <a href="<?php echo e(admin_url('/admin/depoimentos')); ?>"<?php echo nav_active('/admin/depoimentos'); ?>>Depoimentos</a>
  <a href="<?php echo e(admin_url('/admin/projetos')); ?>"<?php echo nav_active('/admin/projetos'); ?>>Projetos</a>
  <a href="<?php echo e(admin_url('/admin/apadrinhamento')); ?>"<?php echo nav_active('/admin/apadrinhamento'); ?>>Apadrinhamento</a>
  <a href="<?php echo e(admin_url('/admin/livros')); ?>"<?php echo nav_active('/admin/livros'); ?>>Livros</a>
  <a href="<?php echo e(admin_url('/admin/midia')); ?>"<?php echo nav_active('/admin/midia'); ?>>Mídia</a>
  <a href="<?php echo e(admin_url('/admin/blog')); ?>"<?php echo nav_active('/admin/blog'); ?>>Blog</a>
  <a href="<?php echo e(admin_url('/admin/mensagens')); ?>"<?php echo nav_active('/admin/mensagens'); ?>>Mensagens</a>
  <a href="<?php echo e(admin_url('/admin/doacoes')); ?>"<?php echo nav_active('/admin/doacoes'); ?>>Doações</a>
  <a href="<?php echo e(admin_url('/admin/traducoes')); ?>"<?php echo nav_active('/admin/traducoes'); ?>>Traduções</a>
  <a href="<?php echo e(admin_url('/admin/usuarios')); ?>"<?php echo nav_active('/admin/usuarios'); ?>>Usuários</a>
  <a href="<?php echo e(admin_url('/admin/auditoria')); ?>"<?php echo nav_active('/admin/auditoria'); ?>>Auditoria</a>
</nav>
<main>
<div class="panel">
<?php echo $content; ?>
</div>
</main>
</div>
</body>
</html>
