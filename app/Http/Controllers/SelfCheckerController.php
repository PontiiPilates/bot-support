<?php

namespace App\Http\Controllers;

use App\DTO\SelfChecker\DocumentGenerateDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Логики здесь не много и декомпозировать на отдельные кейсы практически нечего.
 * По крайней мере, пока.
 * 
 * Также, для MVP можно обойтись без аутентификации.
 * 
 * @todo При случае роста функционала контроллер необходимо сделать тоньше, логику убрать в кейсы.
 * @todo При увеличении запросов добавить аутентификацию sanctum.
 * @todo Не удобно сформирована stableData, хочется видеть ее объектом.
 * 
 * @property array $stableData
 */
class SelfCheckerController extends Controller
{
    private array $stableData;

    /**
     * Смысл данного метода в том, чтобы:
     * - получить данные из Telegram-бота;
     * - сгенерировать PDF-документ на основе полученных данных;
     * - вернуть ссылку на PDF-документ для скачивания.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request): JsonResponse
    {
        $this->prepareData();

        $topResults = $this->topResults($request->all(), 4);

        $dto = new DocumentGenerateDTO($this->stableData, $topResults);

        // dd($dto->toArray());

        $documentId = $this->documentGenerate($dto);

        // процесс генерации документа занимает какое-то время
        // запрос на проверку статуса будет отправляться с указанным интервалом
        // пока документ не приготовится
        while ($this->documentFetch($documentId)->document_card->status != 'success') {
            sleep(10);
        }

        $document = $this->documentFetch($documentId);

        return response()->json([
            'errors' => [],
            'data' => [
                'link_to_pdf' => $document->document_card->public_share_link
            ],
            'success' => true,
            'status' => 200,
        ], 200);
    }

    private function prepareData()
    {
        $this->stableData = [
            'bearer' => config("self_checker.bearer"),
            'documents' => config("self_checker.methods.documents"),
            'documents_cards' => config("self_checker.methods.documents_cards"),
            'template' => config("self_checker.template"),
            'logoUrl' => config("self_checker.logoUrl"),
        ];
    }

    /**
     * Сортирует массив по убыванию и возвращает выбранное количество элементов сверху.
     * 
     * @param array $data
     * @param int $length
     * 
     * @return array
     */
    private function topResults(array $data, int $length): array
    {
        uasort($data, function ($a, $b) {

            if ($a == $b) {
                return 0;
            }

            return ($a < $b) ? 1 : -1;
        });

        return array_slice($data, 0, $length);
    }

    /**
     * Запускает процесс генерации документа и возвращает его id.
     * 
     * @param DocumentGenerateDTO $dto
     * @return string
     */
    public function documentGenerate(DocumentGenerateDTO $dto): string
    {
        $response = Http::withHeaders([
            'Authorization' => $this->stableData['bearer'],
            'Content-Type' => 'application/json'
        ])->post($this->stableData['documents'], $dto->toArray());

        return $response->object()->document->id;
    }

    /**
     * Возвращает информацию о документе по его id.
     * 
     * @param string $documentId
     * @return object
     */
    public function documentFetch(string $documentId): object
    {
        $response = Http::withHeaders([
            'Authorization' => $this->stableData['bearer'],
        ])->get($this->stableData['documents_cards'] . '/' . $documentId);

        return $response->object();
    }
}
