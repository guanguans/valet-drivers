<?php

/** @noinspection PhpUnusedAliasInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2022-2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/valet-drivers
 */

namespace Guanguans\ValetDrivers\Support\Rectors;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\RuleDocGenerator\Exception\PoorDocumentationException;
use Symplify\RuleDocGenerator\Exception\ShouldNotHappenException;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;
use function Guanguans\ValetDrivers\Support\classes;

final class NewExceptionToNewAnonymousExtendsExceptionImplementsRector extends AbstractRector implements ConfigurableRectorInterface, MinPhpVersionInterface
{
    /** @var list<class-string> */
    private array $implements = [];

    /**
     * @throws PoorDocumentationException
     * @throws ShouldNotHappenException
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'New exception to new anonymous extends exception implements',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
                        new \Exception('Testing');
                        CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
                        new class('Testing') extends \Exception implements \Guanguans\ValetDrivers\Contracts\ThrowableContract
                        {
                        };
                        CODE_SAMPLE,
                    [
                        'Guanguans\ValetDrivers\Contracts\ThrowableContract',
                    ],
                ),
            ],
        );
    }

    public function getNodeTypes(): array
    {
        return [
            New_::class,
        ];
    }

    /**
     * @see \PhpParser\NodeVisitor::*
     * @see \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory
     * @see \Rector\Comments\NodeDocBlock\DocBlockUpdater
     * @see \RectorPrefix202503\print_node()
     * @see \RectorPrefix202503\print_node()
     *
     * @param \PhpParser\Node\Expr\New_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // // It's magical.
        // class_exists(ReleaseWorkerInterface::class);

        if (
            !($className = $this->getName($node->class))
            || !is_subclass_of($className, \Throwable::class)
            || $this->isSubclassesOf($className)
        ) {
            return null;
        }

        return new New_(
            new Class_(
                null,
                [
                    'extends' => new FullyQualified($className),
                    'implements' => array_map(
                        static fn (string $implement): FullyQualified => new FullyQualified($implement),
                        $this->implements
                    ),
                ],
                $node->class->getAttributes()
            ),
            $node->getArgs()
        );
    }

    /**
     * @return PhpVersion::PHP_70
     */
    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_70;
    }

    public function configure(array $configuration): void
    {
        // $this->classes()->dd();

        // Assert::allIsAOf($configuration, \Throwable::class);
        Assert::allStringNotEmpty($configuration);

        /** @var list<class-string> $configuration */
        $this->implements = $configuration;
    }

    public function classes(): Collection
    {
        return collect(classes())
            ->filter(
                static fn (string $class): bool => Str::of($class)->startsWith('Rector\\')
                    && Str::of($class)->endsWith([
                        'Factory',
                        'Resolver',
                        // 'er',
                    ])
                    && !Str::of($class)->contains([
                        '\\SwissKnife\\',
                        '\\TypePerfect\\',
                    ])
            )
            ->sort()
            // ->groupBy(fn (string $class) => str($class)->explode('\\')->get(1))
            ->values();
    }

    /**
     * @see \is_subclass_of()
     * @see \is_a()
     *
     * @param class-string $className
     */
    private function isSubclassesOf(string $className): bool
    {
        foreach ($this->implements as $implement) {
            if (!is_subclass_of($className, $implement)) {
                return false;
            }
        }

        return true;
    }
}
