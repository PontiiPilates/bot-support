<?php

declare(strict_types=1);

namespace App\DTO\SelfChecker;

use App\Interfaces\DTOInterface;

class DocumentGenerateDTO implements DTOInterface
{
    public function __construct(
        private readonly array $stableData,
        private readonly array $variableData
    ) {}

    public function toArray(): array
    {
        $compare = [
            'sacrifice' => 'Жертвенность',
            'negativeThoughts' => 'Негативные мысли',
            'doubts' => 'Сомнения',
            'fears' => 'Страхи',
            'grievances' => 'Обиды',
            'emotions' => 'Эмоции',
            'secrets' => 'Секреты',
            'negativeContents' => 'Негативный контент',
            'uselessContents' => 'Бесполезный контент',
            'illusions' => 'Иллюзии',
            'wishes' => 'Желания',
            'deals' => 'Незавершенные дела',
            'shadows' => 'Теневые стороны',
            'psychosomatic' => 'Психосоматика',
            'karma' => 'Кармические задачи',
            'genus' => 'Родовые программы',
            'injury' => 'Травмы',
            'experience' => 'Негативный опыт',
        ];

        $payload = [];
        $payload["logoUrl"] = $this->stableData['logoUrl'];

        foreach ($this->variableData as $key => $value) {
            $labels[] = $compare[$key];
            $counts[] = $value;
            $payload[$key] = $value;
        }

        $payload["anomalyCounts"] = [
            "byType" => [
                "labels" => $labels,
                "counts" => $counts
            ]
        ];

        // dd($this->variableData);

        return [
            "document" => [
                "document_template_id" => $this->stableData['template'],
                "status" => "pending",
                "payload" => $payload,
                // [
                //     "logoUrl" => $this->stableData['logoUrl'],
                //     "sacrifice" => $this->variableData['sacrifice'],
                //     "negativeThoughts" => $this->variableData['negativeThoughts'],
                //     "doubts" => $this->variableData['doubts'],
                //     "fears" => $this->variableData['fears'],
                //     "grievances" => $this->variableData['grievances'],
                //     "emotions" => $this->variableData['emotions'],
                //     "secrets" => $this->variableData['secrets'],
                //     "negativeContents" => $this->variableData['negativeContents'],
                //     "uselessContents" => $this->variableData['uselessContents'],
                //     "illusions" => $this->variableData['illusions'],
                //     "wishes" => $this->variableData['wishes'],
                //     "deals" => $this->variableData['deals'],
                //     "shadows" => $this->variableData['shadows'],
                //     "psychosomatic" => $this->variableData['psychosomatic'],
                //     "karma" => $this->variableData['karma'],
                //     "genus" => $this->variableData['genus'],
                //     "injury" => $this->variableData['injury'],
                //     "experience" => $this->variableData['experience'],
                //     "anomalyCounts" => [
                //         "byType" => [
                //             "labels" => $labels,
                //             "counts" => $counts
                //         ]
                //     ]
                // ],
                "meta" => [
                    "_filename" => "self_checker_results.pdf",
                    "clientRef" => "self_checker_client"
                ]
            ]

        ];
    }
}
