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

        foreach ($this->variableData as $key => $value) {
            $labels[] = $compare[$key];
            $counts[] = $value;
        }

        return [
            "document" => [
                "document_template_id" => $this->stableData['template'],
                "status" => "pending",
                "payload" => [
                    "logoUrl" => $this->stableData['logoUrl'],
                    "sacrifice" => null,
                    "attitudes" => null,
                    "doubts" => null,
                    "fears" => null,
                    "complexes" => null,
                    "grievances" => null,
                    "emotions" => null,
                    "content" => null,
                    "illusions" => null,
                    "desires" => null,
                    "anomalyCounts" => [
                        "byType" => [
                            "labels" => $labels,
                            "counts" => $counts
                        ]
                    ]
                ],
                "meta" => [
                    "_filename" => "Results.pdf",
                    "clientRef" => "selfTestClient"
                ]
            ]

        ];
    }
}
