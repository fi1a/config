<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Writers;

use Fi1a\Config\Exceptions\WriterException;
use Fi1a\Config\Writers\FileWriter;
use Fi1a\Filesystem\Adapters\LocalAdapter;
use Fi1a\Filesystem\FileInterface;
use Fi1a\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

/**
 * Запись конфигурации в файл
 */
class FileWriterTest extends TestCase
{
    /**
     * Возвращает файл
     */
    private function getFile(string $path): FileInterface
    {
        return (new Filesystem(new LocalAdapter(__DIR__ . '/../Resources')))
            ->factoryFile($path);
    }

    /**
     * Осуществляет запись
     */
    public function testWrite(): void
    {
        $php = <<<'PHP'
<?php

return [
  'foo' => [
    'bar' => 'baz',
  ],
  'qux' => 1,
];
PHP;
        $file = $this->getFile('./write.php');
        $writer = new FileWriter($file);
        $this->assertTrue($writer->write($php));
        $this->assertTrue($file->isExist());
        $file->delete();
    }

    /**
     * Осуществляет запись
     */
    public function testWriteFolderNotFound(): void
    {
        $this->expectException(WriterException::class);
        $writer = new FileWriter($this->getFile('./not-exists/write.php'));
        $writer->write('');
    }

    /**
     * Осуществляет запись
     */
    public function testWriteNotAccess(): void
    {
        $php = <<<'PHP'
<?php

return [
  'foo' => [
    'bar' => 'baz',
  ],
  'qux' => 1,
];
PHP;

        $this->expectException(WriterException::class);
        $file = $this->getFile('./write.php');
        chmod($file->getParent()->getPath(), 0111);
        $writer = new FileWriter($file);
        try {
            $writer->write($php);
        } catch (WriterException $exception) {
            chmod($file->getParent()->getPath(), 0775);

            throw $exception;
        }
    }
}
