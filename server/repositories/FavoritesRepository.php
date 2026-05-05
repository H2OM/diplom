<?php

namespace app\repositories;


use app\core\Db;
//TODO Убрать во всех репозиториях проверку на favorite, проверять на фронте через контекст на избранное
/** Репозиторий по управлению избранными товарами */
readonly class FavoritesRepository {
    public function __construct(private Db $db) {}

    /**
     * Получение 'избранного' пользователя
     *
     * @param int $userId
     * @return array
     */
    public function getFavorites(int $userId): array {
        return $this->db->fetchAll("SELECT goods.article FROM goods 
                    JOIN favorites ON goods.id = favorites.goods_id 
                    JOIN users ON favorites.user_id = users.id WHERE users.id = ?", [$userId]);
    }

    /**
     * Добавление 'избранное' пользователя
     *
     * @param int $userId
     * @param string $productId
     * @return string|false
     */
    public function setFavorite(int $userId, string $productId): string|false {
        return $this->db->query()
            ->table('favorites')
            ->insert(['user_id' => $userId, 'product_id' => $productId]);
    }

    /**
     * Удаление 'избранного' пользователя
     *
     * @param int $userId
     * @param string $productId
     * @return bool
     */
    public function unsetFavorite(int $userId, string $productId): bool {
        return $this->db->query()
            ->table('favorites')
                ->where('user_id', '=', $userId)
                ->where('product_id', '=', $productId)
                ->delete();
    }
}
