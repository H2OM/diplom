<?php

namespace app\core;

use Closure;

/** Конструктор запросов */
class QueryBuilder {
    protected string $table = '';
    protected array $select = ['*'];
    protected array $wheres = [];
    protected array $bindings = [];
    protected string $orderBy = '';
    protected string $limit = '';

    public function __construct(private readonly Db $db) {}

    /**
     * SQL команда TABLE
     *
     * @param string $table
     * @return $this
     */
    public function table(string $table): self {
        $this->table = $table;

        return $this;
    }

    /**
     * SQL команда SELECT
     *
     * @param string|array $fields
     * @return $this
     */
    public function select(string|array $fields): self {
        $this->select = is_array($fields) ? $fields : [$fields];

        return $this;
    }

    /**
     * Добавление в существующий SELECT
     *
     * @param string|array $fields
     * @return $this
     */
    public function addSelect(string|array $fields): self {
        if(is_array($fields)) {
            $this->select = $this->select + $fields;
        } else {
            $this->select[] = $fields;
        }

        return $this;
    }

    /**
     * SQL команда WHERE (OR)
     *
     * @param string|Closure $field
     * @param string $operator
     * @param mixed|null $value
     * @return $this
     */
    public function orWhere(string|Closure $field, string $operator = '=', mixed $value = null): self {
        return $this->buildWhere(field: $field, logic: 'OR', operator: $operator, value: $value);
    }
    /**
     * SQL команда WHERE (AND)
     *
     * @param string|Closure $field
     * @param string $operator
     * @param mixed|null $value
     * @return $this
     */
    public function where(string|Closure $field, string $operator = '=', mixed $value = null): self {
        return $this->buildWhere(field: $field, logic: 'AND', operator: $operator, value: $value);
    }

    /**
     * Сборка команды WHERE с заданной логикой
     *
     * @param string|Closure $field
     * @param string $logic
     * @param string $operator
     * @param mixed|null $value
     * @return self
     */
    private function buildWhere(string|Closure $field, string $logic, string $operator = '=', mixed $value = null): self {
        if($field instanceof Closure) {
            $nested = new self($this->db);

            $field($nested);

            $this->wheres[] = [
                'type'     => 'group',
                'logic'    => $logic,
                'query'    => $nested->wheres,
                'bindings' => $nested->bindings
            ];

            $this->bindings = array_merge($this->bindings, $nested->bindings);

            return $this;
        }

        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = [
            'type'     => 'basic',
            'logic'    => $logic,
            'sql'    => "$field $operator ?",
            'operator' => $operator,
            'bindings' => [$value]
        ];

        $this->bindings[] = $value;

        return $this;
    }

    /**
     * Сборка WHERE условий
     *
     * @param array $wheres
     * @return string
     */
    protected function compileWheres(array $wheres): string {
        $sql = '';

        foreach ($wheres as $index => $where) {
            $prefix = $index === 0 ? '' : ' ' . $where['logic'] . ' ';

            if ($where['type'] === 'group') {
                $nestedSql = $this->compileWheres($where['query']);
                $sql .= $prefix . '(' . $nestedSql . ')';
            }

            if ($where['type'] === 'basic') {
                $sql .= $prefix . $where['sql'];
            }
        }

        return $sql;
    }

    /**
     * SQL команда ORDER BY
     *
     * @param string $field
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $field, string $direction = 'ASC'): self {
        $this->orderBy = "ORDER BY $field $direction";

        return $this;
    }

    /**
     * SQL команда LIMIT
     *
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit(int $limit, int $offset = 0): self {
        $this->limit = "LIMIT $offset, $limit";

        return $this;
    }

    /**
     * Сборка SQL строки
     *
     * @return array
     */
    public function toSql(): array {
        $sql = "SELECT " . implode(', ', $this->select) . " FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . $this->compileWheres($this->wheres);
        }

        if ($this->orderBy) {
            $sql .= " " . $this->orderBy;
        }

        if ($this->limit) {
            $sql .= " " . $this->limit;
        }

        return [$sql, $this->bindings];
    }

    /**
     * Выполнение SQL запроса. Получение всех строк
     *
     * @return array
     */
    public function get(): array {
        [$sql, $bindings] = $this->toSql();

        return $this->db->fetchAll($sql, $bindings);
    }

    /**
     * Выполнение SQL запроса. Получение всех строк
     *
     * @return array
     */
    public function first(): array {
        $this->limit(1);

        [$sql, $bindings] = $this->toSql();

        return $this->db->fetchOne($sql, $bindings);
    }

    /**
     * Выполнение SQL запроса. Получение одного значения
     *
     * @param string $column
     * @return mixed
     */
    public function value(string $column): mixed {
        $this->select([$column])->limit(1);

        [$sql, $bindings] = $this->toSql();

        return $this->db->fetchColumn($sql, $bindings);
    }
}