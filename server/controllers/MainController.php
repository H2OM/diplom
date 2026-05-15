<?php
namespace app\controllers;

use app\core\Response;
use app\services\ProductsService;
use app\services\MainService;

/** Контролер для управления главной информацией */
class MainController {
    public function __construct(
        private readonly ProductsService $productsService,
        private readonly MainService     $mainService
    ) {}

    /**
     * Получение основной информации
     *
     * @return Response
     */
    public function infoAction(): Response {
        $products = $this->productsService->getHitAndSales();
        $news = $this->mainService->getNews();

        return Response::jsonSuccess(data: [
            'slider' => $news,
            'popular' => $products['hit'] ?? [],
            'sales' => $products['sales'] ?? []
        ]);
    }
}