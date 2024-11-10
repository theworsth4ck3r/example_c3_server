<?php

namespace Halo\Controllers;

class FilesController
{
	static public function createFile(string $path): string
	{
		if (!file_exists($path)) {
			umask(0);
			touch($path);
			umask(022);
		}

		return $path;
	}

	static public function removeFile(string $path): void
	{
		if (file_exists($path))
			unlink($path);
	}

	static public function modifyFile(string $path, string $mode, string $content): bool
	{
		if (!file_exists($path))
			return false;

		$file = fopen($path, $mode);
		fwrite($file, $content);
		fclose($file);

		return true;
	}

	static public function getFileContents(string $path): string
	{
		if (!file_exists($path))
			return '';

		return file_get_contents($path);
	}
}
