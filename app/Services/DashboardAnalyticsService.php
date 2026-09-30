<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Course;
use App\Enums\NstpComponent;
use App\Models\SchoolYear;
use App\Models\Student;

class DashboardAnalyticsService
{
    /**
     * @return array<string, mixed>
     */
    public function getAnalytics(?int $schoolYearId = null): array
    {
        return [
            'kpis' => $this->getKpis($schoolYearId),
            'componentDistribution' => $this->getComponentDistribution($schoolYearId),
            'genderByComponent' => $this->getGenderByComponent($schoolYearId),
            'enrollmentTrends' => $this->getEnrollmentTrends(),
            'collegeDistribution' => $this->getCollegeDistribution($schoolYearId),
            'topCourses' => $this->getTopCourses($schoolYearId),
        ];
    }

    /**
     * @return array<string, int>
     */
    private function getKpis(?int $schoolYearId): array
    {
        $query = Student::query();

        if ($schoolYearId) {
            $query->where('school_year_id', $schoolYearId);
        }

        $result = $query->selectRaw("
            COUNT(*) as total_students,
            COUNT(*) FILTER (WHERE nstp_component = 'CWTS') as total_cwts,
            COUNT(*) FILTER (WHERE nstp_component = 'ROTC') as total_rotc,
            COUNT(*) FILTER (WHERE nstp_component = 'LTS') as total_lts,
            COUNT(*) FILTER (WHERE gender ILIKE 'Male') as total_male,
            COUNT(*) FILTER (WHERE gender ILIKE 'Female') as total_female
        ")->first();

        return [
            'total_students' => (int) ($result->total_students ?? 0),
            'total_cwts' => (int) ($result->total_cwts ?? 0),
            'total_rotc' => (int) ($result->total_rotc ?? 0),
            'total_lts' => (int) ($result->total_lts ?? 0),
            'total_male' => (int) ($result->total_male ?? 0),
            'total_female' => (int) ($result->total_female ?? 0),
        ];
    }

    /**
     * @return array{series: list<int>, labels: list<string>}
     */
    private function getComponentDistribution(?int $schoolYearId): array
    {
        $kpis = $this->getKpis($schoolYearId);

        return [
            'series' => [$kpis['total_cwts'], $kpis['total_rotc'], $kpis['total_lts']],
            'labels' => ['CWTS', 'ROTC', 'LTS'],
        ];
    }

    /**
     * @return array{categories: list<string>, series: list<array{name: string, data: list<int>}>}
     */
    private function getGenderByComponent(?int $schoolYearId): array
    {
        $components = [NstpComponent::CWTS->value, NstpComponent::ROTC->value, NstpComponent::LTS->value];
        $maleData = [];
        $femaleData = [];

        foreach ($components as $component) {
            $subQuery = Student::where('nstp_component', $component);
            if ($schoolYearId) {
                $subQuery->where('school_year_id', $schoolYearId);
            }

            $maleData[] = (int) (clone $subQuery)->whereRaw("gender ILIKE 'Male'")->count();
            $femaleData[] = (int) (clone $subQuery)->whereRaw("gender ILIKE 'Female'")->count();
        }

        return [
            'categories' => $components,
            'series' => [
                ['name' => 'Male', 'data' => $maleData],
                ['name' => 'Female', 'data' => $femaleData],
            ],
        ];
    }

    /**
     * @return array{categories: list<string>, series: list<array{name: string, data: list<int>}>}
     */
    private function getEnrollmentTrends(): array
    {
        $schoolYears = SchoolYear::orderBy('start_year')->get();

        if ($schoolYears->isEmpty()) {
            $hasStudents = Student::exists();
            $categories = $hasStudents ? ['Current'] : ['No Data'];
            $cwtsCount = Student::where('nstp_component', NstpComponent::CWTS)->count();
            $rotcCount = Student::where('nstp_component', NstpComponent::ROTC)->count();
            $ltsCount = Student::where('nstp_component', NstpComponent::LTS)->count();

            return [
                'categories' => $categories,
                'series' => [
                    ['name' => 'CWTS', 'data' => [$cwtsCount]],
                    ['name' => 'ROTC', 'data' => [$rotcCount]],
                    ['name' => 'LTS', 'data' => [$ltsCount]],
                ],
            ];
        }

        $categories = [];
        $cwtsSeries = [];
        $rotcSeries = [];
        $ltsSeries = [];

        foreach ($schoolYears as $year) {
            $categories[] = $year->label;

            $counts = Student::where('school_year_id', $year->id)
                ->selectRaw("
                    COUNT(*) FILTER (WHERE nstp_component = 'CWTS') as cwts,
                    COUNT(*) FILTER (WHERE nstp_component = 'ROTC') as rotc,
                    COUNT(*) FILTER (WHERE nstp_component = 'LTS') as lts
                ")->first()
            ;

            $cwtsSeries[] = (int) ($counts->cwts ?? 0);
            $rotcSeries[] = (int) ($counts->rotc ?? 0);
            $ltsSeries[] = (int) ($counts->lts ?? 0);
        }

        return [
            'categories' => $categories,
            'series' => [
                ['name' => 'CWTS', 'data' => $cwtsSeries],
                ['name' => 'ROTC', 'data' => $rotcSeries],
                ['name' => 'LTS', 'data' => $ltsSeries],
            ],
        ];
    }

    /**
     * @return array{categories: list<string>, series: list<int>}
     */
    private function getCollegeDistribution(?int $schoolYearId): array
    {
        $query = Student::query();

        if ($schoolYearId) {
            $query->where('school_year_id', $schoolYearId);
        }

        $courseCounts = $query->whereNotNull('course')
            ->where('course', '!=', '')
            ->groupBy('course')
            ->selectRaw('course, COUNT(*) as total')
            ->pluck('total', 'course')
            ->all()
        ;

        $colleges = [
            'SAAD' => 0,
            'SAS' => 0,
            'SAME' => 0,
            'SOED' => 0,
            'SOE' => 0,
            'SOT' => 0,
            'Other' => 0,
        ];

        foreach ($courseCounts as $courseCode => $count) {
            $collegeAcronym = $this->resolveCollege((string) $courseCode);
            $colleges[$collegeAcronym] = ($colleges[$collegeAcronym] ?? 0) + (int) $count;
        }

        if ($colleges['Other'] === 0) {
            unset($colleges['Other']);
        }

        return [
            'categories' => array_keys($colleges),
            'series' => array_values($colleges),
        ];
    }

    /**
     * @return array{categories: list<string>, series: list<int>}
     */
    private function getTopCourses(?int $schoolYearId): array
    {
        $query = Student::query();

        if ($schoolYearId) {
            $query->where('school_year_id', $schoolYearId);
        }

        $top = $query->whereNotNull('course')
            ->where('course', '!=', '')
            ->groupBy('course')
            ->selectRaw('course, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit(8)
            ->get()
        ;

        if ($top->isEmpty()) {
            return [
                'categories' => ['No Records'],
                'series' => [0],
            ];
        }

        return [
            'categories' => $top->pluck('course')->all(),
            'series' => $top->pluck('count')->map(fn ($v) => (int) $v)->all(),
        ];
    }

    private function resolveCollege(string $courseCode): string
    {
        $clean = trim($courseCode);

        foreach (Course::cases() as $case) {
            if (strcasecmp($case->value, $clean) === 0 || strcasecmp($case->name, $clean) === 0) {
                if (preg_match('/\((.*?)\)/', $case->school(), $matches)) {
                    return $matches[1];
                }
            }
        }

        $upper = strtoupper($clean);
        if (\in_array($upper, ['BSHRT', 'HRT', 'HM'], true)) {
            return 'SOT';
        }
        if (\in_array($upper, ['ABECON', 'ECON'], true)) {
            return 'SAS';
        }
        if (str_starts_with($upper, 'BSED') || str_starts_with($upper, 'BT') || str_starts_with($upper, 'BEED')) {
            return 'SOED';
        }
        if (\in_array($upper, ['BSCE', 'BSME', 'BSIE', 'BSIT', 'BSCHE', 'BSGE', 'BSEE', 'BSECE'], true)) {
            return 'SOE';
        }

        return 'Other';
    }
}
