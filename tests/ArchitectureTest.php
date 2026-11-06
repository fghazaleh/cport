<?php

declare(strict_types=1);

namespace FG\Support;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Fluent classes never depend on each other: they only exchange plain values
 * and PHP interfaces (iterable, Stringable). They may use the static Arr, Str and Math.
 */
final class ArchitectureTest extends TestCase
{
    private const FLUENT = ['Collection', 'Text', 'Number', 'Chars', 'LazyCollection'];

    /**
     * @return array<string, array{string}>
     */
    public static function fluentClasses(): array
    {
        return array_combine(self::FLUENT, array_map(static fn(string $c): array => [$c], self::FLUENT));
    }

    #[DataProvider('fluentClasses')]
    public function testFluentClassDoesNotReferenceOtherFluentClasses(string $class): void
    {
        $others = array_diff(self::FLUENT, [$class]);
        $tokens = token_get_all((string) file_get_contents(__DIR__ . "/../src/{$class}.php"));

        foreach ($tokens as $token) {
            if (\is_array($token) && \in_array($token[0], [T_STRING, T_NAME_QUALIFIED, T_NAME_FULLY_QUALIFIED], true)) {
                $name = ltrim(strrchr('\\' . $token[1], '\\'), '\\');
                $this->assertNotContains($name, $others, "{$class} must not reference {$name}");
            }
        }
    }

    #[DataProvider('fluentClasses')]
    public function testStaticClassesDoNotReferenceFluentClasses(string $class): void
    {
        foreach (['Arr', 'Str', 'Math'] as $static) {
            $source = (string) file_get_contents(__DIR__ . "/../src/{$static}.php");
            $this->assertDoesNotMatchRegularExpression("/\\b{$class}::/", $source);
        }
    }
}
