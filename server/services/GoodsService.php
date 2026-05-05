<?php

namespace app\services;

use app\core\Db;
use app\repositories\FiltersRepository;
use app\repositories\GoodsRepository;

/** Сервис для управления товарами */
readonly class GoodsService {
    public function __construct(
        private FiltersRepository $filtersRepository,
        private GoodsRepository $goodsRepository,
        private AuthService $authService
    ) {}

    /**
     * Получение популярных и скидочных товаров
     *
     * @return array[]
     */
    public function getHitAndSalesGoods(): array {
        $result = $this->goodsRepository->getHitAndSalesGoods($this->authService->id());

        $hit = [];
        $sales = [];

        foreach($result as $k => $v) {
            if($v['Hit']) {
                $hit[] = $v;
            }
            if($v['Sale']) {
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
        return $this->goodsRepository->getGoodsByFilters($filters, $this->authService->id());
    }

    /**
     * Получение всех фильтров
     *
     * @param array $filters
     * @return array
     */
    public function getFilters(array $filters): array {
        $filters = $this->filtersRepository->getFilters($filters);

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
     */
    public function getProductByArticle(string $article): array {
        return $this->goodsRepository->getProductByArticle($article, $this->authService->id());
    }

    /**
     * Получение связанных товаров по артикулу
     *
     * @param string $article
     * @return array
     */
    public function getRelatedProductsByArticle(string $article): array {
        return $this->goodsRepository->getRelatedProductsByArticle($article, $this->authService->id());
    }
}