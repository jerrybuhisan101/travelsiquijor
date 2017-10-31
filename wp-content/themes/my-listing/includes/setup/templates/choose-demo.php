<div class="theme-browser">
	<?php if (isset($_GET['msg']) && $_GET['msg'] == 'done'): ?>
		<div class="notice-success notice is-dismissible">
			<p>Demo data was imported successfully.</p>
		</div>
	<?php endif ?>


	<?php if (!c27()->plugins_active()): ?>

		<div class="notice-error notice">
			<p>You must activate the <a href="<?php echo esc_url( admin_url( 'admin.php?page=tgmpa-install-plugins' ) ) ?>">required plugins</a> to import demo content.</p>
		</div>

	<?php else: ?>

		<div class="notice-warning notice">
			<p>Note: Importing may take a few minutes. Do not close the window while it's executing.</p>
		</div>

		<div class="themes wp-clearfix">

			<?php foreach (CASE27_Setup::instance()->get_demos() as $demo): $active = CASE27_Setup::instance()->is_demo_imported($demo['slug']); ?>

				<div class="theme <?php echo $active ? 'active' : '' ?>">
					<div class="theme-screenshot" style="background-image: url('<?php echo esc_url( $demo['background-url'] ) ?>'); background-size: cover; background-position: center center;">
					</div>
					<span class="more-details" id="twentyfifteen-action">Theme Details</span>
					<h2 class="theme-name" style="height: 34px; font-weight: 500;">
						<?php echo esc_html( $demo['name'] ) ?> <?php echo $active ? '<span style="font-weight: 300;">(Imported)</span>' : '' ?>
						<p style="margin-top: 3px; font-weight: 400;"><?php echo esc_html( $demo['description'] ) ?></p>
					</h2>
					<div class="theme-actions" style="height: 52px;">
						<a class="button button-primary" onclick="return confirm('This may override the existing posts, pages, and other content. Do you want to continue?')"
						   href="<?php echo add_query_arg( 'demo', $demo['slug'], admin_url( 'admin.php?import=case27_demo_import' ) ) ?>">
						   <?php echo $active ? 'Re-Import' : 'Import' ?>
						 </a>
					</div>
				</div>

			<?php endforeach ?>

		</div>

	<?php endif ?>

</div>
