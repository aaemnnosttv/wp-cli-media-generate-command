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
	 * @var \cli\progress\Bar
	 */
	protected $progress;

	/**
	 * @var array [singular, plural]
	 */
	protected $type;

	/**
	 * Generate images.
	 *
	 * [--count=<number>]
	 * : Number of images to generate.
	 * ---
	 * default: 100
	 * ---
	 *
	 * [--size=<size>]
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
	 * [--format=<format>]
	 * : Render output in a particular format.
	 * ---
	 * default: progress
	 * options:
	 *   - progress
	 *   - ids
	 * ---
	 *
	 * @param $_
	 * @param $assoc
	 */
	public function image($_, $assoc)
	{
		$this->assoc = $assoc;
		$this->tempDir = $this->generateTempDirectory();
		$this->type = ['image', 'images'];
		$this->initProgress();

		list($width, $height) = explode('x', $assoc['size']);
		$batch = substr(uniqid(), -5);
		$args = compact('batch','width','height');

		$results = [];
		foreach (range(1, $assoc['count']) as $i) {
			 $results[] = $this->generateImage($i, $args);
		}

		$generated = array_filter($results);

		$this->formatOutput($generated);
	}

	protected function formatOutput($generated)
	{
		$this->progress->finish();

		$generated_count = count($generated);

		if ('ids' == $this->assoc['format']) {
			echo join(' ', $generated);
			return;
		}

		if ($generated_count < $this->assoc['count']) {
			WP_CLI::warning('Some items were not generated.');
		}

		WP_CLI::success(vsprintf("Generated %s %s.", [
			$generated_count,
			$this->typeForm($generated_count),
		]));
	}

	protected function typeForm($count)
	{
		list($singular, $plural) = $this->type;

		return _n($singular, $plural, $count);
	}

	/**
	 * Initialize the progress bar for display.
	 *
	 * If progress is not the designated output format, a null progress bar is used instead.
	 *
	 * @param string $message
	 */
	protected function initProgress($message = '')
	{
		if (! $message) {
			$message = 'Generating ' . $this->typeForm(2);
		}

		if ('progress' == $this->assoc['format']) {
			$this->progress = \WP_CLI\Utils\make_progress_bar($message, $this->assoc['count']);
			$this->progress->display();
		} else {
			$this->progress = new WP_CLI\NoOp();
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

		$this->progress->tick();

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
