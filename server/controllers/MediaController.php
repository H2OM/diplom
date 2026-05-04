<?php
namespace app\controllers;

use app\core\Response;
use app\services\GoodsService;
use app\services\MediaService;

/** Контролер для управления главной информацией */
readonly class MediaController {
    public function __construct(private MediaService $mediaService, private GoodsService $goodsService) {}

    /**
     * Получение основной информации
     *
     * @return Response
     */
    public function mainInfoAction(): Response {
        $news = $this->mediaService->getMainNews();
        $goods = $this->goodsService->getHitAndSalesGoods();

        return Response::json(['slider' => $news, 'popular' => $goods['hit'] ?? [], 'sales' => $goods['sales'] ?? []]);
    }
}