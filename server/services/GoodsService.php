<?php

namespace app\services;

use app\core\Db;
use app\core\enums\ResponseMessage;
use app\core\exceptions\ResponseException;
use app\core\Session;
use app\repositories\FiltersRepository;
use app\repositories\GoodsRepository;

/** Сервис для управления товарами */
class GoodsService {
    public function __construct(
        private readonly FiltersRepository $filtersRepository,
        private readonly GoodsRepository   $goodsRepository,
        private readonly FavoritesService  $favoritesService,
        private readonly Session           $session,
    ) {}

    /**
     * Получение популярных и скидочных товаров
     *
     * @return array[]
     */
    public function getHitAndSales(): array {
        $result = $this->goodsRepository->getHitAndSales();

        $hit = [];
        $sales = [];

        foreach($result as $k => $v) {
            if($v['hit']) {
                $hit[] = $v;
            }
            if($v['sale']) {
                $sales[] = $v;
            }
        }

        return ['hit' => $hit, 'sales' => $sales];
    }

    /**
     * Получение каталога товаров с фильтрацией
     *
     * @param array $filters
     * @return array
     */
    public function getCatalogByFilters(array $filters): array {
        $catalog = $this->goodsRepository->getByFilters($filters);

        if(count($catalog) === 0 || !isset($filters['favorite'])) return $catalog;

        $favorites = $this->session->get('favorites');

        if(empty($favorites)) $favorites = $this->favoritesService->get();

        $showOnlyFavorites = filter_var($filters['favorite'], FILTER_VALIDATE_BOOLEAN);

        return array_filter($catalog, function ($product) use ($favorites, $showOnlyFavorites) {
            $isFavorite = in_array($product['id'], $favorites);

            return $showOnlyFavorites ? $isFavorite : !$isFavorite;
        });
    }

    /**
     * Получение всех фильтров
     *
     * @param array $filters
     * @return array
     * @throws ResponseException
     */
    public function getFilters(array $filters): array {
        $filters = $this->filtersRepository->getFilters($filters);

        if(count($filters) === 0) {
            throw new ResponseException(ResponseMessage::ERROR_DATA, 403);
        }

        foreach($filters as $filter) {
            if($filter['name'] === "" && $filter['value_code'] === "") {
                continue;
            }

            if(!isset($filters[$filter['type']])) {
                $filters[$filter['type']] = [];
            }

            $index = "";

            foreach($filters[$filter['type']] as $k => $type) {
                if(isset($type['code']) && $type['code'] == $filter['code']) {
                    $index = $k;

                    break;
                }
            }

            if($index !== "") {
                $filters[$filter['type']][$index]["values"][] = ["name" => $filter["name"], "value_code" => $filter['value_code']];

            } else {
                $filters[$filter['type']][] = [
                    "filter" => $filter['filter'],
                    "code" => $filter['code'],
                    "values" => [
                        [
                            "name" => $filter["name"],
                            "value_code" => $filter['value_code']
                        ]
                    ]
                ];
            }
        }

        return $filters;
    }

    /**
     * Получение отдельного товара по id
     *
     * @param int $id
     * @return array
     * @throws ResponseException
     */
    public function getProductById(int $id): array {
        $product = $this->goodsRepository->getProductById($id);

        if(!$product) {
            throw new ResponseException(ResponseMessage::ERROR_GET_DATA);
        }

        if(count($product) === 0) {
            throw new ResponseException(ResponseMessage::ERROR_PRODUCT_NOT_FOUND, 404);
        }

        return $product;
    }

    /**
     * Получение связанных товаров по id
     *
     * @param int $id
     * @return array
     */
    public function getRelatedById(int $id): array {
        return $this->goodsRepository->getRelatedById($id);
    }
}