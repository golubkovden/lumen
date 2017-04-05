<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen;

use FondBot\Contracts\Exceptions\FileExistsException;
use FondBot\Contracts\Exceptions\FileNotFoundException;
use FondBot\Contracts\Filesystem as FilesystemContract;
use Illuminate\Contracts\Filesystem\FileNotFoundException as LumenFileNotFoundException;
use Illuminate\Filesystem\Filesystem as LumenFilesystem;

class Filesystem implements FilesystemContract
{
    protected $filesystem;

    public function __construct(LumenFilesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Read a file.
     *
     * @param string $path The path to the file.
     *
     * @throws FileNotFoundException
     *
     * @return string The file contents or false on failure.
     */
    public function read(string $path): string
    {
        try {
            return $this->filesystem->get($path);
        } catch (LumenFileNotFoundException $exception) {
            throw new FileNotFoundException($exception->getMessage());
        }
    }

    /**
     * Write a new file.
     *
     * @param string $path The path of the new file.
     * @param string $contents The file contents.
     *
     * @throws FileExistsException
     */
    public function write(string $path, string $contents): void
    {
        if ($this->filesystem->exists($path)) {
            throw new FileExistsException('File `' . $path . '` already exists.');
        }

        $directory = $this->filesystem->dirname($path);
        if (!$this->filesystem->isDirectory($directory)) {
            $this->filesystem->makeDirectory($directory, 0755, true);
        }

        $this->filesystem->put($path, $contents);
    }

    /**
     * Delete a file.
     *
     * @param string $path
     *
     * @throws FileNotFoundException
     */
    public function delete(string $path): void
    {
        $this->filesystem->delete($path);
    }
}