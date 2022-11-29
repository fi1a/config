<?php

declare(strict_types=1);

use Fi1a\Config\Parsers\FileExtensionRegistry;
use Fi1a\Config\Parsers\JSONParser;
use Fi1a\Config\Parsers\PHPParser;

FileExtensionRegistry::add('php', PHPParser::class);
FileExtensionRegistry::add('json', JSONParser::class);
