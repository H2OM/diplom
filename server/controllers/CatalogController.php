<?php

namespace app\controllers;

use app\core\Request;
use app\core\Response;
use app\services\GoodsService;


/** Управление каталогом */
readonly class CatalogController {
    public function __construct(private Request $request, private GoodsService $goodsService) {}

    /**
     * Получение каталога товара
     *
     * @return Response
     */
    protected function getCatalogAction(): Response {
        $filters = $this->request->get();

        try {
            $catalog = $this->goodsService->getCatalogByFilters($filters);
            $filters = $this->goodsService->getFilters($filters);

            return Response::json(data: ['success' => true, 'data' => [
                'catalog' => $catalog,
                'filters' => $filters,
                'count' => count($catalog)
            ]]);
        } catch (\Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }

    /**
     * Получение отдельного товара
     *
     * @return Response
     */
    protected function getProductAction(): Response {
        $article = $this->request->get('article');

        if(!$article) {
            return Response::json(data: ['error' => true, 'message' => 'Недостаточно данных'], status: 403);
        }

        try {
            $product = $this->goodsService->getRelatedProductsByArticle($article);
            $relatedProducts = $this->goodsService->getRelatedProductsByArticle($article);

            return Response::json(data: ['success' => true, 'data' => [
                'product' => $product,
                'related' => $relatedProducts,
            ]]);
        } catch (\Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }
}