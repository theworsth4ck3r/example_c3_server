<?php

namespace Halo\Controllers;


class DirectoriesController
{
	static public function createDirectory(string $path): string
	{
		if (!file_exists($path)) {
			umask(0);
			mkdir($path, 0777, true);
			umask(022);
		}

		return $path;
	}

	public static function removeDirRecursively($dir): bool
	{
		$files = array_diff(scandir($dir), array('.', '..'));

		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? self::removeDirRecursively("$dir/$file") : unlink("$dir/$file");
		}

		return rmdir($dir);
	}

	static public function removeDirectory(string $path, bool $recursively = false): void
	{
		if (!file_exists($path)) return;

		if ($recursively) {
			self::removeDirRecursively($path);
		} else {
			self::clearDirectory($path);
			rmdir($path);
		}
	}

	static public function clearDirectory(string $path): bool
	{
		$files = glob($path . '/{,.}*', GLOB_BRACE);

		foreach ($files as $file) {
			if (is_file($file)) unlink($file);
		}

		return true;
	}
}
