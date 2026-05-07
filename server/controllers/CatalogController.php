<?php

namespace app\controllers;

use app\core\enums\ResponseMessage;
use app\core\exceptions\ResponseException;
use app\core\Request;
use app\core\Response;
use app\services\GoodsService;


/** Управление каталогом */
class CatalogController {
    public function __construct(private readonly Request $request, private readonly GoodsService $goodsService) {}

    /**
     * Получение каталога товара
     *
     * @return Response
     */
    public function getCatalogAction(): Response {
        $filters = $this->request->get();

        try {
            $catalog = $this->goodsService->getCatalogByFilters($filters);
            $filters = $this->goodsService->getFilters($filters);

            return Response::jsonSuccess(data: [
                'catalog' => $catalog,
                'filters' => $filters,
                'count' => count($catalog)
            ]);
        } catch (ResponseException $e) {
            return Response::jsonError(message: $e->getResponseMessage(), status: $e->getCode() ?: 400);
        }
    }

    /**
     * Получение отдельного товара
     *
     * @return Response
     */
    public function getProductAction(): Response {
        $article = $this->request->get('article');

        if(!$article) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_ENOUGH_DATA, status: 403);
        }

        try {
            $product = $this->goodsService->getProductByArticle($article);
            $relatedProducts = $this->goodsService->getRelatedByArticle($article);

            return Response::jsonSuccess(data: [
                'product' => $product,
                'related' => $relatedProducts,
            ]);
        } catch (ResponseException $e) {
            return Response::jsonError(message: $e->getResponseMessage(), status: $e->getCode() ?: 400);
        }
    }
}