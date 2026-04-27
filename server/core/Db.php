<?php
    namespace app\core;

    use PDO;
    use PDOStatement;

    /** Работа с базой данных */
    class Db {
        public PDO $pdo;

        public function __construct(array $db_config) {
            try {
                $this->pdo = new PDO(
                    $db_config['dsn'],
                    $db_config['user'],
                    $db_config['pass'],
                    $db_config['opts']
                );
            } catch (\PDOException $e) {
                throw new \PDOException("Error with data base connection", 500);
            }
        }

        /**
         * Получение сборщика запроса
         *
         * @return QueryBuilder
         */
        public function query(): QueryBuilder {
            return new QueryBuilder($this);
        }

        /**
         * Выполнение запроса и возвращение всех строк
         *
         * @param string $sql
         * @param array $params
         * @return array
         */
        public function fetchAll(string $sql, array $params = []): array {
            return $this->preparedExecute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Выполнение запроса и возвращение одной строки
         *
         * @param string $sql
         * @param array $params
         * @return array|null
         */
        public function fetchOne(string $sql, array $params = []): ?array {
            return $this->preparedExecute($sql, $params)->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        /**
         * Выполнение запроса и возвращение первой колонки первой строки
         *
         * @param string $sql
         * @param array $params
         * @return mixed
         */
        public function fetchColumn(string $sql, array $params = []): mixed {
            return $this->preparedExecute($sql, $params)->fetchColumn();
        }

        public function fetchInsertId(string $sql, array $params = []): string|false {
            $this->preparedExecute($sql, $params);

            return $this->pdo->lastInsertId();
        }

        /**
         * Выполнение запроса и возвращение состояния
         *
         * @param string $request
         * @param array $params
         * @return PDOStatement
         */
        public function preparedExecute(string $request, array $params = []): PDOStatement {
            $state = $this->pdo->prepare($request);
            $state->execute($params);

            return $state;
        }






        public function getQuery($request, $FKAAN = false, $COUNT = false, $ARRAY_ONLY = false) {

            $result = $this->pdo->query($request);

            if($COUNT) {
                return $result->fetch(PDO::FETCH_COLUMN);
            } 

            $out = [];

            while($row = $result->fetch()) {

                if($ARRAY_ONLY) {

                    array_push($out, array_shift($row));
                    continue;
                }                

                $FKAAN ? $out[array_shift($row)] = $row : array_push($out, $row);
            }

            $isResultAnArray = (!empty($out) && is_array($out[array_key_first($out)]));

            if(!$isResultAnArray || ($ARRAY_ONLY && empty($out))) {

                $out = [$out];
            }

            return $out;   
        }

        public function getPreparedQuery($request, $parrametrs = [], $count = false, $FKAAN = false) {
            try {
                $state = $this->pdo->prepare($request);

                for($i = 1; $i <= count($parrametrs); $i++) {

                    $state->bindParam($i, $parrametrs[$i-1]['VALUE'],
                        ($parrametrs[$i-1]['INT'] ?? false) ? PDO::PARAM_INT : PDO::PARAM_STR, $parrametrs[$i-1]['PARAMVALUE'] ?? 0);
                }

                $state->execute();

                $result = [];

                while($row = $state->fetch()) {

                    $FKAAN ? $result[array_shift($row)] = $row : array_push($result, $row);
                }

                if(count($result) == 1) {

                    $result = $result[0];
                }

                if($count) {
                    
                    $result = $result[array_key_first($result)];
                }

                return $result;

            } catch (\PDOException $e) {

                throw new \PDOException();
            }
        }
        
        public function beginTransaction(): void {
            $this->pdo->beginTransaction();
        }
        public function commitTransaction(): void {
            $this->pdo->commit();
        }
        public function rollbackTransaction(): void {
            $this->pdo->rollBack();
        }
    }