<?php

class RuleEngine
{
    /**
     * Evaluates a rule with nested AND/OR conditions and performs actions if conditions match.
     *
     * @param array $rule
     *    Expected format:
     *    [
     *      'conditions' => [ // condition tree
     *          'type' => 'and'|'or', // optional for root, default 'and'
     *          'children' => [ // array of conditions or nested condition trees
     *              [ 'field' => ..., 'operator' => ..., 'value' => ... ], // leaf condition
     *              [ 'type' => 'and'|'or', 'children' => [...]], // nested conditions
     *          ],
     *      ],
     *      'actions' => [
     *          ['field' => 'Feld7', 'value' => 'green'],
     *          // weitere Aktionen...
     *      ],
     *    ]
     * @param array $item The data item (z.B. Datenbankzeile) auf die Bedingungen angewandt werden.
     * @param callable $actionHandler Funktion, die Actions ausführt: function(array $action, array &$item): void
     *
     * @return bool true wenn die Regel angewandt wurde, sonst false
     */
    public function evaluateRule(array $rule, array &$item, callable $actionHandler): bool
    {
        $conditions = $rule['conditions'] ?? ['type' => 'and', 'children' => []];
        $actions = $rule['actions'] ?? [];

        $matches = $this->evaluateConditionNode($conditions, $item);

        if ($matches) {
            foreach ($actions as $action) {
                $actionHandler($action, $item);
            }
            return true;
        }

        return false;
    }

    /**
     * Rekursive Auswertung eines Condition-Knotens.
     *
     * @param array $node
     *    Format:
     *    - Leaf: ['field'=>..., 'operator'=>..., 'value'=>...]
     *    - Branch: ['type'=>'and'|'or', 'children'=>[...]]
     * @param array $item
     * @return bool
     */
    protected function evaluateConditionNode(array $node, array $item): bool
    {
        if (isset($node['field'], $node['operator'], $node['value'])) {
            // Blattknoten - einfache Bedingung prüfen
            return $this->evaluateCondition($node, $item);
        }

        $type = strtolower($node['type'] ?? 'and');
        $children = $node['children'] ?? [];

        if (empty($children)) {
            // Wenn keine Kinder, gilt true (keine Bedingungen)
            return true;
        }

        if ($type === 'and') {
            foreach ($children as $child) {
                if (!$this->evaluateConditionNode($child, $item)) {
                    return false;
                }
            }
            return true;
        }

        if ($type === 'or') {
            foreach ($children as $child) {
                if ($this->evaluateConditionNode($child, $item)) {
                    return true;
                }
            }
            return false;
        }

        throw new \InvalidArgumentException("Unbekannter Node-Type: $type");
    }

    /**
     * Prüft eine einzelne Bedingung auf dem Item.
     *
     * @param array $condition ['field'=>..., 'operator'=>..., 'value'=>...]
     * @param array $item
     * @return bool
     */
    protected function evaluateCondition(array $condition, array $item): bool
    {
        $field = $condition['field'];
        $operator = $condition['operator'];
        $value = $condition['value'];

        $itemValue = $item[$field] ?? null;

        switch ($operator) {
            case '=':
            case '==':
                return $itemValue == $value;
            case '===':
                return $itemValue === $value;
            case '!=':
            case '<>':
                return $itemValue != $value;
            case '!==':
                return $itemValue !== $value;
            case '>':
                return $itemValue > $value;
            case '>=':
                return $itemValue >= $value;
            case '<':
                return $itemValue < $value;
            case '<=':
                return $itemValue <= $value;
            case 'in':
                return is_array($value) && in_array($itemValue, $value);
            case 'not in':
                return is_array($value) && !in_array($itemValue, $value);
            case 'contains':
                return is_string($itemValue) && strpos($itemValue, $value) !== false;
            default:
                throw new \InvalidArgumentException("Unbekannter Operator: $operator");
        }
    }
}
