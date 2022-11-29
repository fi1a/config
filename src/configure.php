<?php

declare(strict_types=1);

use Fi1a\Config\Parsers\FileTypeRegistry;
use Fi1a\Config\Parsers\JSONParser;
use Fi1a\Config\Parsers\PHPParser;

FileTypeRegistry::add('php', PHPParser::class);
FileTypeRegistry::add('json', JSONParser::class);
