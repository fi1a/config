<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Writers;

use Fi1a\Config\Exceptions\WriterException;
use Fi1a\Config\Writers\FileWriter;
use PHPUnit\Framework\TestCase;

/**
 * Запись конфигурации в файл
 */
class FileWriterTest extends TestCase
{
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
        $filePath = __DIR__ . '/../Fixtures/write.php';
        $writer = new FileWriter($filePath);
        $this->assertTrue($writer->write($php));
        $this->assertTrue(is_file($filePath));
        unlink($filePath);
    }

    /**
     * Осуществляет запись
     */
    public function testWriteFolderNotFound(): void
    {
        $this->expectException(WriterException::class);
        $filePath = __DIR__ . '/../not-exists/write.php';
        new FileWriter($filePath);
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
        $filePath = __DIR__ . '/../Fixtures/write.php';
        chmod(dirname($filePath), 0000);
        $writer = new FileWriter($filePath);
        try {
            $writer->write($php);
        } catch (WriterException $exception) {
            chmod(dirname($filePath), 0775);

            throw $exception;
        }
    }
}
