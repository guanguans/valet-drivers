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

use Illuminate\Support\Collection;
use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\Declare_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Exception\PoorDocumentationException;
use Symplify\RuleDocGenerator\Exception\ShouldNotHappenException;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

class AddNoinspectionsDocCommentToDeclareRector extends AbstractRector implements ConfigurableRectorInterface
{
    /** @var list<string> */
    private array $inspections = [];

    /**
     * @throws PoorDocumentationException
     * @throws ShouldNotHappenException
     */
    final public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add noinspections doc comment to declare',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
                         /** @noinspection AnonymousFunctionStaticInspection */
                         /** @noinspection StaticClosureCanBeUsedInspection */
                         /** @noinspection PhpVoidFunctionResultUsedInspection */
                         declare(strict_types=1);
                        CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
                         /** @noinspection AnonymousFunctionStaticInspection */
                         /** @noinspection NullPointerExceptionInspection */
                         /** @noinspection PhpPossiblePolymorphicInvocationInspection */
                         /** @noinspection PhpUnhandledExceptionInspection */
                         /** @noinspection StaticClosureCanBeUsedInspection */
                         /** @noinspection PhpVoidFunctionResultUsedInspection */
                         declare(strict_types=1);
                        CODE_SAMPLE,
                    [
                        'AnonymousFunctionStaticInspection',
                        'NullPointerExceptionInspection',
                        'PhpPossiblePolymorphicInvocationInspection',
                        'PhpUnhandledExceptionInspection',
                        'StaticClosureCanBeUsedInspection',
                    ],
                ),
            ],
        );
    }

    /**
     * @param list<string> $configuration
     */
    final public function configure(array $configuration): void
    {
        Assert::allStringNotEmpty($configuration);
        sort($configuration);
        $this->inspections = $configuration;
    }

    final public function getNodeTypes(): array
    {
        return [
            Declare_::class,
        ];
    }

    /**
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection NullPointerExceptionInspection
     *
     * @param \PhpParser\Node\Stmt\Declare_ $node
     */
    final public function refactor(Node $node): Node
    {
        $originalCommentContents = collect($node->getComments())
            ->map(static fn (Comment $comment): string => $comment->getText())
            ->implode(\PHP_EOL);

        $node->setAttribute(
            'comments',
            collect($this->inspections)
                ->reduce(
                    static fn (Collection $comments, string $inspection): Collection => str_contains(
                        $originalCommentContents,
                        $inspection
                    ) ? $comments : $comments->add(new Doc("/** @noinspection $inspection */")),
                    collect($node->getComments())
                )
                ->sort(function (Comment $a, Comment $b): int {
                    if (!$this->search($a) && $this->search($b)) {
                        return 1;
                    }

                    if ($this->search($a) && !$this->search($b)) {
                        return -1;
                    }

                    if (($aIndex = $this->search($a)) && ($bIndex = $this->search($b))) {
                        return $aIndex <=> $bIndex;
                    }

                    return strcmp($a->getText(), $b->getText());
                })
                ->all()
        );

        return $node;
    }

    private function search(Comment $comment): int
    {
        foreach ($this->inspections as $index => $inspection) {
            if (str_contains($comment->getText(), $inspection)) {
                return $index + 1;
            }
        }

        return 0;
    }
}
