<?php

namespace app\repositories;

use app\core\Db;

/** Репозиторий для управления товарами */
readonly class GoodsRepository {
    public function __construct(private Db $db) {}

    /**
     * Получение популярных и скидочных товаров
     *
     * @param int|null $userId
     * @return array
     */
    public function getHitAndSalesGoods(int|null $userId): array {
        $userQueryPart = ($userId ? " IF(favorites.goods_id = goods.id, true, false) AS favorites, " : "");
        $userFavoritesQueryPart = ($userId ? " AND favorites.user_id = '".$userId."' " : "");

        return $this->db->fetchAll("SELECT goods.id, goods.title, goods.brand, goods.type, goods.article, goods.price, goods.price_old, goods.image, goods.slider_images, 
                                    GROUP_CONCAT(filters_values.value SEPARATOR '.') AS Size, $userQueryPart
                                    IF(goods.hit = '1', true, false) AS hit, IF(goods.price_old > 0, true, false) AS sale, categories.code AS category 
                                    FROM goods JOIN filters_goods JOIN filters_values JOIN filters ON filters_values.filter_id = filters.id 
                                    AND filters.code = 'size' AND filters_goods.goods_id = goods.id AND filters_goods.goods_id = goods.id 
                                    AND filters_goods.filter_value_id = filters_values.id JOIN categories ON goods.category_id = categories.id 
                                    LEFT JOIN favorites ON goods.id = favorites.goods_id 
                                    $userFavoritesQueryPart
                                    WHERE (goods.hit = '1' OR goods.price_old > 0) GROUP BY goods.id");
    }

    /**
     * Получение товаров по фильтрам
     *
     * @param array $filters
     * @param int|null $userId
     * @return array
     */
    public function getGoodsByFilters(array $filters, int|null $userId): array {
        $sqlPartSelect = "";
        $sqlPartCondition = "";
        $prepareSelectedValue = [];
        $prepareConditionedValue = [];

        $index = 1;

        foreach ($filters as $key => $value) {
            if($key === 'sort') continue;

            switch($key) {
                case "category":
                    $sqlPartCondition .= match (strtolower($value)) {
                        "man" => "(categories.Code = 'Man' OR categories.Code = 'All') AND ",
                        "woman" => "(categories.Code = 'Woman' OR categories.Code = 'All') AND ",
                        "kids" => "categories.Code = 'Kids' AND ",
                        "all" => "categories.Code = 'All' AND ",
                        default => ''
                    };

                    break;
                case "price":
                    $price = explode(",", $value);

                    if(count($price) == 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                        $sqlPartCondition .= "goods.price <= ? AND goods.price >= ? AND ";

                        array_push($prepareConditionedValue, $price[1], $price[0]);
                    }

                    break;
                case "sale":
                    switch($value) {
                        case "Yes":
                            $sqlPartCondition .= "goods.price <= (goods.price_old * 1) AND ";

                            break;
                        case "More10":
                            $sqlPartCondition .= "goods.price <= (goods.price_old * 0.9) AND ";

                            break;
                        case "More30":
                            $sqlPartCondition .= "goods.price <= (goods.price_old * 0.7) AND ";

                            break;
                        case "More50":
                            $sqlPartCondition .= "goods.price <= (goods.price_old * 0.5) AND ";

                            break;
                        default:
                            break;
                    }

                    break;
                case "favorites":
                    if($userId && $value === 'true') {
                        $sqlPartCondition .= "favorites.user_id = '$userId' AND ";
                    }

                    break;
                case "brand":
                case "size":
                case "color":
                case "type":
                    $filter = explode(",", $value);

                    if(count($filter) >= 1) {
                        $sqlPartSelect .= "(SELECT goods.id AS ID FROM goods JOIN filters_goods ON filters_goods.goods_id = goods.id 
                                                    JOIN filters_values ON filters_values.id = filters_goods.filter_value_id JOIN filters 
                                                    ON filters.id = filters_values.filter_id WHERE (";

                        foreach($filter as $field) {
                            $sqlPartSelect .= "COALESCE(filters_values.code, filters_values.id) = ? OR ";

                            $prepareSelectedValue[] = $field;
                        }

                        $sqlPartSelect = substr_replace($sqlPartSelect, ")", -4);
                        $sqlPartSelect .= " AND filters.code = ? GROUP BY goods.id) AS {$index}s, ";

                        $prepareSelectedValue[] = $key;

                        $sqlPartCondition .= "goods.id = {$index}s.ID AND ";
                    }

                    break;
                default:
                    break;
            }

            $index++;
        }

        if(strlen($sqlPartCondition) > 0) {
            $sqlPartCondition = " WHERE ". substr_replace($sqlPartCondition, "", -5);
        }

        $sqlPartCondition .= " GROUP BY goods.id";

        if(isset($filters['sort'])) {
            $sqlPartCondition .= match ($filters['sort']) {
                'low_to_high' => ' ORDER BY goods.price ASC',
                'high_to_low' => ' ORDER BY goods.price DESC',
                default => ''
            };
        }

        $userQueryPart = ($userId ? ", IF(favorites.Goods_id = goods.id, true, false) AS 'favorites'" : "");

        return $this->db->fetchAll("SELECT goods.*, categories.code AS category, GROUP_CONCAT(filters_values.value SEPARATOR '.') AS size 
                                            $userQueryPart 
                                            FROM ".$sqlPartSelect."filters_goods JOIN goods 
                                            JOIN filters_values ON filters_goods.goods_id = goods.id 
                                            AND filters_goods.filter_value_id = filters_values.id 
                                            JOIN filters ON filters_values.filter_id = filters.id AND filters.code = 'size' JOIN 
                                            categories ON goods.category_id = categories.id LEFT JOIN favorites ON goods.id = favorites.goods_id".$sqlPartCondition,
        [...$prepareSelectedValue, ...$prepareConditionedValue]);
    }

    /**
     * Получение отдельного товара по артикулу
     *
     * @param string $article
     * @param int|null $userId
     * @return array
     */
    public function getProductByArticle(string $article, ?int $userId): array {
        $userQueryPart = ($userId ? " IF(favorites.goods_id = goods.id, true, false) AS favorites, " : "");

        $product = $this->db->fetchOne("SELECT goods.*, base.value AS color, categories.code AS category, $userQueryPart
                                                GROUP_CONCAT(filters_values.value SEPARATOR '.') AS Size, mods.colors 
                                                FROM (SELECT GROUP_CONCAT(CONCAT(goods.article, '#', categories.code, '#', goods.image) SEPARATOR ',') AS colors 
                                                FROM goods_variations JOIN goods ON (goods_variations.base_id = goods.id OR goods_variations.variation_id = goods.id) 
                                                JOIN categories ON goods.category_id = categories.id 
                                                WHERE (goods_variations.base_article = ? OR goods_variations.variation_article = ?) ORDER BY goods_variations.base_article DESC) AS mods, 
                                                (SELECT filters_values.value FROM goods JOIN filters_goods ON filters_goods.goods_id = goods.id 
                                                JOIN filters_values ON filters_goods.filter_value_id = filters_values.id JOIN filters ON filters_values.filter_id = filters.id 
                                                AND filters.code = 'color' WHERE goods.article = ?) AS base, 
                                                goods JOIN categories ON goods.category_id = categories.id JOIN filters_goods ON filters_goods.goods_id = goods.id 
                                                JOIN filters_values ON filters_goods.filter_value_id = filters_values.id 
                                                JOIN filters ON filters_values.filter_id = filters.id AND filters.code = 'size' 
                                                LEFT JOIN favorites ON favorites.goods_id = goods.id AND favorites.user_id = 5 WHERE goods.article = ?",
            array_fill(0, 4, $article));

        if(!empty($product['colors'])) {
            $colors = array_unique(explode(',', $product['colors']));

            sort($colors);

            $product['colors'] = implode(',', $colors);
        }

        return $product;
    }

    /**
     * Получение товара по артикулу и размеру
     *
     * @param string $article
     * @param string $size
     * @param int|null $userId
     * @return array|null
     */
    public function getProductByArticleAndSize(string $article, string $size, ?int $userId): array|null {
        $userQueryPart = ($userId ? ", IF(favorites.goods_id = goods.id, true, false) AS favorites" : "");

        return $this->db->fetchOne("SELECT goods.*, categories.code AS 'category' $userQueryPart
                                                FROM goods JOIN filters_goods ON filters_goods.goods_id = goods.id 
                                                JOIN filters_values ON filters_goods.filter_value_id = filters_values.id AND  filters_values.value = ?
                                                JOIN filters ON filters_values.filter_id = filters.id JOIN categories ON goods.category_id = categories.id
                                                LEFT JOIN favorites ON goods.id = favorites.goods_id
                                                WHERE goods.article = ? AND filters.code = 'size'",
            [$size, $article]);
    }

    /**
     * Получение связанных товаров по артикулу
     *
     * @param string $article
     * @param int|null $userId
     * @return array
     */
    public function getRelatedProductsByArticle(string $article, ?int $userId): array {
        $userQueryPart = ($userId ? " IF(favorites.goods_id = goods.id, true, false) AS favorites, " : "");
        $userFavoritesQueryPart = ($userId ? " AND favorites.user_id = '$userId' " : "");

        return $this->db->fetchAll("SELECT goods.id, goods.title, goods.brand, goods.type, goods.article, goods.price, goods.price_old, 
                            goods.image, goods.slider_images, categories.code AS category, $userQueryPart 
                            GROUP_CONCAT(filters_values.value SEPARATOR '.') AS Size FROM goods_related 
                            JOIN goods ON goods_related.goods_id = goods.id OR goods_related.related_id = goods.id 
                            JOIN filters_goods JOIN filters_values JOIN filters ON filters_values.filter_id = filters.id 
                            AND filters.code = 'size' AND filters_goods.goods_id = goods.id 
                            AND filters_goods.goods_id = goods.id AND filters_goods.filter_value_id = filters_values.id 
                            JOIN categories ON goods.Category_id = categories.id 
                            LEFT JOIN favorites ON goods.id = favorites.Goods_id 
                            $userFavoritesQueryPart
                            WHERE (goods_related.goods_article = ? OR goods_related.related_article = ?) 
                            AND goods.article != ? GROUP BY goods.id",
            array_fill(0, 3, $article));
    }
}