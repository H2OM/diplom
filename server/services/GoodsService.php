<?php

namespace app\services;

use app\core\Db;
use app\core\enums\ResponseMessage;
use app\core\exceptions\ResponseException;
use app\repositories\FiltersRepository;
use app\repositories\GoodsRepository;

/** Сервис для управления товарами */
class GoodsService {
    public function __construct(
        private readonly FiltersRepository $filtersRepository,
        private readonly GoodsRepository   $goodsRepository,
        private readonly AuthService       $authService
    ) {}

    /**
     * Получение популярных и скидочных товаров
     *
     * @return array[]
     */
    public function getHitAndSales(): array {
        $result = $this->goodsRepository->getHitAndSales($this->authService->id());

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
     * @throws ResponseException
     */
    public function getCatalogByFilters(array $filters): array {
        $catalog = $this->goodsRepository->getByFilters($filters, $this->authService->id());

        if(count($catalog) === 0) {
            throw new ResponseException(ResponseMessage::ERROR_DATA, 403);
        }

        return $catalog;
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
     * Получение отдельного товара по артикулу
     *
     * @param string $article
     * @return array
     * @throws ResponseException
     */
    public function getProductByArticle(string $article): array {
        $product = $this->goodsRepository->getProductByArticle($article, $this->authService->id());

        if(!$product) {
            throw new ResponseException(ResponseMessage::ERROR_GET_DATA);
        }

        if(count($product) === 0) {
            throw new ResponseException(ResponseMessage::ERROR_PRODUCT_NOT_FOUND, 404);
        }

        return $product;
    }

    /**
     * Получение связанных товаров по артикулу
     *
     * @param string $article
     * @return array
     */
    public function getRelatedByArticle(string $article): array {
        return $this->goodsRepository->getRelatedByArticle($article, $this->authService->id());
    }
}