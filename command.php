<?php

use WP_CLI_MediaGenerate\MediaGenerateCommand;

if (! class_exists('WP_CLI')) {
	return;
}

require_once(__DIR__ . '/vendor/autoload.php');

WP_CLI::add_command('media generate', MediaGenerateCommand::class);

WP_CLI::add_hook('before_invoke:media generate image', function() {
	if (! class_exists('Imagick')) {
		WP_CLI::error("This command requires the Imagick extension");
	}
});
