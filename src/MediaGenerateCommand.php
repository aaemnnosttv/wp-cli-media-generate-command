<?php

namespace WP_CLI_MediaGenerate;

use Imagick;
use WP_CLI;
use WP_CLI\Utils;

/**
 * Generate and import media items into the media library.
 */
class MediaGenerateCommand
{
	/**
	 * @var array Assoc args called with the command.
	 */
	protected $assoc;

	/**
	 * @var string Temporary directory for generating media in before importing.
	 */
	protected $tempDir;

	/**
	 * Generate images.
	 *
	 * <count>
	 * : Number of images to generate.
	 *
	 * [<dimensions>]
	 * : Image dimensions in LxW format.
	 * ---
	 * default: 50x50
	 * ---
	 *
	 * [--ext=<extension>]
	 * : File extension to generate images with.
	 * ---
	 * default: jpg
	 * ---
	 *
	 * @param $_
	 * @param $assoc
	 */
	public function image($_, $assoc)
	{
		list($count, $dimensions) = $_;
		list($width, $height) = explode('x', $dimensions);
		$this->assoc = $assoc;
		$this->tempDir = $this->generateTempDirectory();

		$batch = substr(uniqid(), -5);
		$args = compact('batch','width','height');

		foreach (range(1, $count) as $i) {
			$this->generateImage($i, $args);
		}
	}

	/**
	 * Generate an individual image.
	 *
	 * @param int $i The index of the image to generate
	 * @param array $args
	 *
	 * @return bool|int|object
	 */
	protected function generateImage($i, $args)
	{
		$imagick = new Imagick();
		/**
		 * @link(http://php.net/manual/en/imagick.newpseudoimage.php, link)
		 * @link(http://www.imagemagick.org/script/formats.php, link)
		 */
		$imagick->newPseudoImage((int) $args['width'], (int) $args['height'], 'plasma:fractal');
		$imagick->setImageFormat($this->assoc['ext']);

		$filepath = "$this->tempDir/{$args['batch']}-$i.{$this->assoc['ext']}";
		file_put_contents($filepath, $imagick->getImageBlob());

		$id = media_handle_sideload([
			'tmp_name' => $filepath,
			'name' => Utils\basename($filepath),
		], 0);

		if (is_wp_error($id)) {
			return false;
		}

		return $id;
	}

	/**
	 * Generate the temporary directory for media to be generated in.
	 *
	 * @return string
	 */
	protected function generateTempDirectory()
	{
		$tempdir = vsprintf('%s/media-generate-%s', [
			sys_get_temp_dir(),
			microtime(true),
		]);

		if (! wp_mkdir_p($tempdir)) {
			WP_CLI::error("Failed to create the temp directory $tempdir");
		}

		add_action('shutdown', function() use ($tempdir) {
			WP_CLI::debug("Cleaning up $tempdir");
			foreach(glob("$tempdir/*") as $file) {
				unlink($file);
			}
			rmdir($tempdir);
		});

		return $tempdir;
	}
}
