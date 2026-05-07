<?php
namespace app\controllers;

use app\core\Response;
use app\services\GoodsService;
use app\services\MediaService;

/** Контролер для управления главной информацией */
class MediaController {
    public function __construct(private readonly MediaService $mediaService, private readonly GoodsService $goodsService) {}

    /**
     * Получение основной информации
     *
     * @return Response
     */
    public function mainInfoAction(): Response {
        $news = $this->mediaService->getNews();
        $goods = $this->goodsService->getHitAndSales();

        return Response::jsonSuccess(data: [
            'slider' => $news,
            'popular' => $goods['hit'] ?? [],
            'sales' => $goods['sales'] ?? []
        ]);
    }
}