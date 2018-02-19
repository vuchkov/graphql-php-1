<?php

namespace Digia\GraphQL\Language\AST\Builder;

use Digia\GraphQL\Language\AST\Builder\Behavior\ParseKindTrait;
use Digia\GraphQL\Language\AST\Builder\Behavior\ParseLocationTrait;
use Digia\GraphQL\Language\AST\KindEnum;
use Digia\GraphQL\Language\AST\Node\Contract\NodeInterface;
use Digia\GraphQL\Language\AST\Node\Contract\SelectionNodeInterface;
use Digia\GraphQL\Language\AST\Node\SelectionSetNode;

class SelectionSetNodeBuilder extends AbstractNodeBuilder
{

    use ParseKindTrait;
    use ParseLocationTrait;

    /**
     * @param array $ast
     * @return NodeInterface
     */
    public function build(array $ast): NodeInterface
    {
        return new SelectionSetNode([
            'kind'       => $this->parseKind($ast),
            'selections' => $this->parseSelections($ast),
            'loc'        => $this->parseLocation($ast),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function supportsKind(string $kind): bool
    {
        return KindEnum::SELECTION_SET === $kind;
    }

    /**
     * @param array $ast
     * @return array|SelectionNodeInterface[]
     */
    protected function parseSelections(array $ast): array
    {
        $selections = [];

        if (isset($ast['selections'])) {
            foreach ($ast['selections'] as $selectionAst) {
                $selections[] = $this->factory->build($selectionAst);
            }
        }

        return $selections;
    }
}