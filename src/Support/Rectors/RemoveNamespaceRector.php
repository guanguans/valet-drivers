<?php

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

use Illuminate\Support\Str;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Nop;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Exception\PoorDocumentationException;
use Symplify\RuleDocGenerator\Exception\ShouldNotHappenException;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class RemoveNamespaceRector extends AbstractRector implements ConfigurableRectorInterface
{
    /** @var list<string> */
    private array $namespaces = [];

    /**
     * @throws PoorDocumentationException
     * @throws ShouldNotHappenException
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove namespace',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
                        namespace Guanguans\ValetDriversTests\Support;

                        it('can get classes', function (): void {
                            expect(classes())->toBeArray()->toBeTruthy();
                        })->group(__DIR__, __FILE__);
                        CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
                        it('can get classes', function (): void {
                            expect(classes())->toBeArray()->toBeTruthy();
                        })->group(__DIR__, __FILE__);
                        CODE_SAMPLE,
                    [
                        'Guanguans\ValetDriversTests',
                    ],
                ),
            ],
        );
    }

    /**
     * @param list<string> $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allStringNotEmpty($configuration);
        $this->namespaces = $configuration;
    }

    public function getNodeTypes(): array
    {
        return [
            Namespace_::class,
        ];
    }

    /**
     * @param \PhpParser\Node\Stmt\Namespace_ $node
     *
     * @return null|list<Node>
     */
    public function refactor(Node $node): ?array
    {
        if (!Str::of($this->getName($node))->startsWith($this->namespaces)) {
            return null;
        }

        if ((empty($node->stmts) || $node->stmts[0] instanceof Nop) && $node->getDocComment() instanceof Doc) {
            $nop = new Nop;
            $nop->setDocComment($node->getDocComment());

            array_unshift($node->stmts, $nop);
        }

        return $node->stmts;
    }
}
