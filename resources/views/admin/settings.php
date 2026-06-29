<h1>Configurações Globais</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" action="<?php echo e(admin_url('/admin/configuracoes')); ?>" enctype="multipart/form-data">
  <?php echo csrf_field(); ?>

  <fieldset>
    <legend>Identidade</legend>
    <div class="field">
      <label for="site_name">Nome do site</label>
      <input type="text" id="site_name" name="site_name" value="<?php echo e($settings['site_name'] ?? ''); ?>">
    </div>
    <div class="grid-2">
      <div class="field">
        <label for="site_logo">Logo (versão colorida — aparece quando a página é rolada)</label>
        <?php if (!empty($settings['site_logo'])): ?>
          <div class="hint"><img src="<?php echo e(admin_url('/' . $settings['site_logo'])); ?>" alt="" style="height:40px; background:#eee; padding:4px; border-radius:4px;"></div>
        <?php else: ?>
          <div class="hint">Nenhuma logo enviada — o site usa a logo padrão do tema.</div>
        <?php endif; ?>
        <input type="file" id="site_logo" name="site_logo" accept="image/jpeg,image/png">
      </div>
      <div class="field">
        <label for="site_logo_white">Logo (versão branca — topo da página, navbar escura)</label>
        <?php if (!empty($settings['site_logo_white'])): ?>
          <div class="hint"><img src="<?php echo e(admin_url('/' . $settings['site_logo_white'])); ?>" alt="" style="height:40px; background:#1f2630; padding:4px; border-radius:4px;"></div>
        <?php else: ?>
          <div class="hint">Nenhuma logo branca enviada — o site usa a logo padrão do tema.</div>
        <?php endif; ?>
        <input type="file" id="site_logo_white" name="site_logo_white" accept="image/jpeg,image/png">
      </div>
      <div class="field">
        <label for="site_favicon">Favicon</label>
        <?php if (!empty($settings['site_favicon'])): ?>
          <div class="hint"><img src="<?php echo e(admin_url('/' . $settings['site_favicon'])); ?>" alt="" style="height:32px; background:#eee; padding:4px; border-radius:4px;"></div>
        <?php else: ?>
          <div class="hint">Nenhum favicon enviado.</div>
        <?php endif; ?>
        <input type="file" id="site_favicon" name="site_favicon" accept="image/jpeg,image/png">
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Estatística (contador animado da Home e Sobre)</legend>
    <div class="field">
      <label for="stat_children_served">Número (sem pontos ou vírgulas — a animação formata sozinha)</label>
      <input type="text" id="stat_children_served" name="stat_children_served" value="<?php echo e($settings['stat_children_served'] ?? '1432805'); ?>" style="max-width:220px;">
    </div>
    <div class="hint">Os textos antes/depois do número ("Atendemos mais de" / "crianças em 190 países...") são editáveis em <a href="<?php echo e(admin_url('/admin/traducoes')); ?>">Traduções</a>, nas chaves <code>Stat Served Prefix</code> e <code>Stat Children Countries Suffix</code> — um valor por idioma.</div>
  </fieldset>

  <fieldset>
    <legend>Marca (cores)</legend>
    <div class="hint">Aplicadas como reforço sobre botões/destaques principais do tema atual. Deixe em branco para manter o visual padrão.</div>
    <div class="grid-2">
      <div class="field">
        <label for="color_primary">Cor primária (ex: #f96d00)</label>
        <input type="text" id="color_primary" name="color_primary" value="<?php echo e($settings['color_primary'] ?? ''); ?>">
      </div>
      <div class="field">
        <label for="color_secondary">Cor secundária</label>
        <input type="text" id="color_secondary" name="color_secondary" value="<?php echo e($settings['color_secondary'] ?? ''); ?>">
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Redes sociais</legend>
    <div class="grid-2">
      <div class="field"><label for="social_facebook">Facebook</label><input type="url" id="social_facebook" name="social_facebook" value="<?php echo e($settings['social_facebook'] ?? ''); ?>"></div>
      <div class="field"><label for="social_instagram">Instagram</label><input type="url" id="social_instagram" name="social_instagram" value="<?php echo e($settings['social_instagram'] ?? ''); ?>"></div>
      <div class="field"><label for="social_twitter">Twitter/X</label><input type="url" id="social_twitter" name="social_twitter" value="<?php echo e($settings['social_twitter'] ?? ''); ?>"></div>
      <div class="field"><label for="social_youtube">YouTube</label><input type="url" id="social_youtube" name="social_youtube" value="<?php echo e($settings['social_youtube'] ?? ''); ?>"></div>
      <div class="field"><label for="social_spotify">Spotify</label><input type="url" id="social_spotify" name="social_spotify" value="<?php echo e($settings['social_spotify'] ?? ''); ?>"></div>
      <div class="field"><label for="social_whatsapp">WhatsApp (link)</label><input type="url" id="social_whatsapp" name="social_whatsapp" value="<?php echo e($settings['social_whatsapp'] ?? ''); ?>"></div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Contato</legend>
    <div class="field"><label for="contact_address">Endereço</label><input type="text" id="contact_address" name="contact_address" value="<?php echo e($settings['contact_address'] ?? ''); ?>"></div>
    <div class="grid-2">
      <div class="field"><label for="contact_phone">Telefone</label><input type="tel" id="contact_phone" name="contact_phone" value="<?php echo e($settings['contact_phone'] ?? ''); ?>"></div>
      <div class="field"><label for="contact_whatsapp_display">WhatsApp (exibição)</label><input type="text" id="contact_whatsapp_display" name="contact_whatsapp_display" value="<?php echo e($settings['contact_whatsapp_display'] ?? ''); ?>"></div>
      <div class="field"><label for="contact_email">E-mail</label><input type="email" id="contact_email" name="contact_email" value="<?php echo e($settings['contact_email'] ?? ''); ?>"></div>
    </div>
  </fieldset>

  <fieldset>
    <legend>SEO / Open Graph</legend>
    <div class="field"><label for="seo_meta_title">Meta título</label><input type="text" id="seo_meta_title" name="seo_meta_title" value="<?php echo e($settings['seo_meta_title'] ?? ''); ?>"></div>
    <div class="field"><label for="seo_meta_description">Meta descrição</label><textarea id="seo_meta_description" name="seo_meta_description"><?php echo e($settings['seo_meta_description'] ?? ''); ?></textarea></div>
    <div class="field">
      <label for="seo_og_image">Imagem Open Graph</label>
      <?php if (!empty($settings['seo_og_image'])): ?><div class="hint">Atual: <?php echo e($settings['seo_og_image']); ?></div><?php endif; ?>
      <input type="file" id="seo_og_image" name="seo_og_image" accept="image/jpeg,image/png">
    </div>
  </fieldset>

  <fieldset>
    <legend>Analytics</legend>
    <div class="grid-2">
      <div class="field"><label for="ga_id">Google Analytics ID</label><input type="text" id="ga_id" name="ga_id" value="<?php echo e($settings['ga_id'] ?? ''); ?>" placeholder="G-XXXXXXX"></div>
      <div class="field"><label for="gtm_id">Google Tag Manager ID</label><input type="text" id="gtm_id" name="gtm_id" value="<?php echo e($settings['gtm_id'] ?? ''); ?>" placeholder="GTM-XXXXXXX"></div>
    </div>
  </fieldset>

  <button type="submit">Salvar configurações</button>
</form>
