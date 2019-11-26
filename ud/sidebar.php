<div id="_sidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <?= PermLink('visit', 'Home', "/ud/"); ?>
  <?= PermLink('dl_builds', 'Download builds', "/ud/dl_builds"); ?>
  <?= PermLink('publish_builds', 'Upload build', "/ud/p_builds"); ?>
  <?= PermLink('add_news', 'Post news', "/ud/post_news"); ?>
  <?= PermLink('manage_users', 'Manage users', "/ud/manage_users"); ?>
  <?= PermLink('shop', 'Token shop', "/ud/shop"); ?>
  <?= PermLink('manage_shop', 'Manage token shop', "/ud/shop"); ?>
  <?= PermLink('elvex_manage_social', 'Manage ELVEX Social', "/ud/elvex/social"); ?>
  <?= PermLink('elvex_users_social', 'Manage ELVEX Users', "/ud/elvex/users"); ?>
  <?= PermLink('report_bug', 'Bug tracker', "/ud/elvex/bugtracker"); ?>
  <?= PermLink('manage_reports', 'Bug tracker', "/ud/elvex/bugtracker"); ?>
</div>