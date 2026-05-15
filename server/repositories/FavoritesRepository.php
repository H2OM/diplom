<?php

namespace app\repositories;


use app\core\Db;
use app\core\Hydrator;

/** Репозиторий по управлению избранными товарами */
class FavoritesRepository {
    private const FAVORITES_PRODUCTS_WITH_VARIATIONS_SQL = "SELECT products.*, categories_types.name AS category, brands.name as brand, products_stocks.count as stock,
                    IF(COUNT(var_p.id) > 0,
                       JSON_ARRAYAGG(JSON_OBJECT('id', var_p.id, 'image', var_p.image)),
                       JSON_ARRAY()
                    ) AS variations
                FROM products 
                JOIN categories_types ON products.category_type_id = categories_types.id
                LEFT JOIN products_stocks ON products_stocks.product_id = products.id
                LEFT JOIN brands ON products.brand_id = brands.id
                LEFT JOIN favorites ON products.id = favorites.product_id
                LEFT JOIN products_variations ON (products.id = products_variations.product_id OR products.id = products_variations.variation_id)
                LEFT JOIN products AS var_p ON (products_variations.variation_id = var_p.id OR products_variations.product_id = var_p.id) AND var_p.id != products.id
                WHERE favorites.user_id = ? 
                GROUP BY products.id;";

    public function __construct(private readonly Db $db, private readonly Hydrator $hydrator) {}

    /**
     * Получение
     *
     * @param int $userId
     * @return array
     */
    public function get(int $userId): array {
        return $this->hydrator->decodeJson(
            $this->db->fetchAll(self::FAVORITES_PRODUCTS_WITH_VARIATIONS_SQL, [$userId]),
            ['variations']
        );
    }

    /**
     * Добавление
     *
     * @param int $userId
     * @param int $productId
     * @return int
     */
    public function add(int $userId, int $productId): int {
        return $this->db->query()
            ->table('favorites')
            ->insert(['user_id' => $userId, 'product_id' => $productId])
            ->affectedRows();
    }

    /**
     * Удаление
     *
     * @param int $userId
     * @param int $productId
     * @return int
     */
    public function remove(int $userId, int $productId): int {
        return $this->db->query()
            ->table('favorites')
                ->where('user_id', '=', $userId)
                ->where('product_id', '=', $productId)
                ->delete()
                ->affectedRows();
    }

    /**
     * Отчистка
     *
     * @param int $userId
     * @return bool
     */
    public function clear(int $userId): bool {
        return $this->db->query()
            ->table('favorites')
                ->where('user_id', '=', $userId)
                ->delete()
                ->execute();
    }
}
