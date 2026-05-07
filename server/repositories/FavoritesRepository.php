<?php

namespace app\repositories;


use app\core\Db;
//TODO Убрать во всех репозиториях проверку на favorite, проверять на фронте через контекст на избранное
/** Репозиторий по управлению избранными товарами */
class FavoritesRepository {
    public function __construct(private readonly Db $db) {}

    /**
     * Получение
     *
     * @param int $userId
     * @return array
     */
    public function get(int $userId): array {
        return $this->db->fetchAll("SELECT goods.id FROM goods 
                    JOIN favorites ON goods.id = favorites.product_id 
                    JOIN users ON favorites.user_id = users.id WHERE users.id = ?", [$userId]);
    }

    /**
     * Добавление
     *
     * @param int $userId
     * @param string $productId
     * @return string|false
     */
    public function add(int $userId, string $productId): string|false {
        return $this->db->query()
            ->table('favorites')
            ->insert(['user_id' => $userId, 'product_id' => $productId]);
    }

    /**
     * Удаление
     *
     * @param int $userId
     * @param string $productId
     * @return bool
     */
    public function remove(int $userId, string $productId): bool {
        return $this->db->query()
            ->table('favorites')
                ->where('user_id', '=', $userId)
                ->where('product_id', '=', $productId)
                ->delete();
    }
}
