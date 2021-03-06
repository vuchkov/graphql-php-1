<?php

namespace Digia\GraphQL\Validation\Rule;

use Digia\GraphQL\Language\Node\FieldNode;
use Digia\GraphQL\Language\Visitor\VisitorResult;
use Digia\GraphQL\Type\Definition\LeafTypeInterface;
use Digia\GraphQL\Validation\ValidationException;
use function Digia\GraphQL\Type\getNamedType;
use function Digia\GraphQL\Validation\noSubselectionAllowedMessage;
use function Digia\GraphQL\Validation\requiresSubselectionMessage;

/**
 * Scalar leafs
 *
 * A GraphQL document is valid only if all leaf fields (fields without
 * sub selections) are of scalar or enum types.
 */
class ScalarLeafsRule extends AbstractRule
{
    /**
     * @inheritdoc
     */
    protected function enterField(FieldNode $node): VisitorResult
    {
        $type         = $this->context->getType();
        $selectionSet = $node->getSelectionSet();

        if (null !== $type) {
            if (getNamedType($type) instanceof LeafTypeInterface) {
                if (null !== $selectionSet) {
                    $this->context->reportError(
                        new ValidationException(
                            noSubselectionAllowedMessage((string)$node, (string)$type),
                            [$selectionSet]
                        )
                    );
                }
            } elseif (null === $selectionSet) {
                $this->context->reportError(
                    new ValidationException(
                        requiresSubselectionMessage((string)$node, (string)$type),
                        [$node]
                    )
                );
            }
        }

        return new VisitorResult($node);
    }
}
