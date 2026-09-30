<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-7">
        {{-- Component Donut Chart --}}
        <div class="bg-white rounded-lg border border-[#f9e6ec] shadow p-5">
            <h3 class="font-display uppercase tracking-wider text-xs text-[#800033] mb-4 border-b border-[#fdf2f5] pb-2">
                Component Share
            </h3>
            <x-charts.apex 
                chartId="component-donut" 
                :chartOptions="[
                    'chart' => ['type' => 'donut', 'height' => 300, 'fontFamily' => 'Source Sans 3, sans-serif'],
                    'series' => $this->chartAnalytics['componentDistribution']['series'],
                    'labels' => $this->chartAnalytics['componentDistribution']['labels'],
                    'colors' => ['#800033', '#f9c22e', '#0038a8'],
                    'legend' => ['position' => 'bottom', 'fontFamily' => 'Oswald'],
                    'stroke' => ['width' => 2, 'colors' => ['#ffffff']],
                    'plotOptions' => [
                        'pie' => [
                            'donut' => [
                                'size' => '65%',
                                'labels' => [
                                    'show' => true,
                                    'total' => ['show' => true, 'label' => 'Total', 'fontFamily' => 'Oswald']
                                ]
                            ]
                        ]
                    ]
                ]" 
            />
        </div>

        <div class="bg-white rounded-lg border border-[#f9e6ec] shadow p-5">
            <h3 class="font-display uppercase tracking-wider text-xs text-[#800033] mb-4 border-b border-[#fdf2f5] pb-2">
                Gender Breakdown by Component
            </h3>
            <x-charts.apex 
                chartId="gender-bar" 
                :chartOptions="[
                    'chart' => ['type' => 'bar', 'height' => 300, 'stacked' => true, 'fontFamily' => 'Source Sans 3, sans-serif', 'toolbar' => ['show' => false]],
                    'series' => $this->chartAnalytics['genderByComponent']['series'],
                    'colors' => ['#0284c7', '#ec4899'],
                    'xaxis' => ['categories' => $this->chartAnalytics['genderByComponent']['categories']],
                    'legend' => ['position' => 'bottom', 'fontFamily' => 'Oswald'],
                    'plotOptions' => ['bar' => ['borderRadius' => 4, 'columnWidth' => '45%']]
                ]" 
            />
        </div>

        <div class="bg-white rounded-lg border border-[#f9e6ec] shadow p-5">
            <h3 class="font-display uppercase tracking-wider text-xs text-[#800033] mb-4 border-b border-[#fdf2f5] pb-2">
                College Trainee Distribution
            </h3>
            <x-charts.apex 
                chartId="college-bar" 
                :chartOptions="[
                    'chart' => ['type' => 'bar', 'height' => 300, 'fontFamily' => 'Source Sans 3, sans-serif', 'toolbar' => ['show' => false]],
                    'series' => [['name' => 'Students', 'data' => $this->chartAnalytics['collegeDistribution']['series']]],
                    'colors' => ['#4a001c'],
                    'xaxis' => ['categories' => $this->chartAnalytics['collegeDistribution']['categories']],
                    'plotOptions' => ['bar' => ['borderRadius' => 4, 'distributed' => true, 'columnWidth' => '55%']],
                    'legend' => ['show' => false]
                ]" 
            />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-7">
        <div class="bg-white rounded-lg border border-[#f9e6ec] shadow p-5">
            <h3 class="font-display uppercase tracking-wider text-xs text-[#800033] mb-4 border-b border-[#fdf2f5] pb-2">
                Historical Enrollment Growth Trend
            </h3>
            <x-charts.apex 
                chartId="enrollment-trend" 
                :chartOptions="[
                    'chart' => ['type' => 'area', 'height' => 300, 'fontFamily' => 'Source Sans 3, sans-serif', 'toolbar' => ['show' => false]],
                    'series' => $this->chartAnalytics['enrollmentTrends']['series'],
                    'colors' => ['#800033', '#f9c22e', '#0038a8'],
                    'stroke' => ['curve' => 'smooth', 'width' => 2.5],
                    'markers' => ['size' => 5, 'strokeWidth' => 2, 'hover' => ['size' => 7]],
                    'fill' => ['type' => 'gradient', 'gradient' => ['opacityFrom' => 0.45, 'opacityTo' => 0.05]],
                    'xaxis' => ['categories' => $this->chartAnalytics['enrollmentTrends']['categories']],
                    'legend' => ['position' => 'top', 'fontFamily' => 'Oswald']
                ]" 
            />
        </div>

        <div class="bg-white rounded-lg border border-[#f9e6ec] shadow p-5">
            <h3 class="font-display uppercase tracking-wider text-xs text-[#800033] mb-4 border-b border-[#fdf2f5] pb-2">
                Top Degree Programs by Trainee Population
            </h3>
            <x-charts.apex 
                chartId="courses-bar" 
                :chartOptions="[
                    'chart' => ['type' => 'bar', 'height' => 300, 'fontFamily' => 'Source Sans 3, sans-serif', 'toolbar' => ['show' => false]],
                    'series' => [['name' => 'Trainees', 'data' => $this->chartAnalytics['topCourses']['series']]],
                    'colors' => ['#f9c22e'],
                    'plotOptions' => ['bar' => ['horizontal' => true, 'borderRadius' => 4, 'barHeight' => '60%']],
                    'xaxis' => ['categories' => $this->chartAnalytics['topCourses']['categories']],
                    'dataLabels' => ['enabled' => true, 'style' => ['colors' => ['#2d0012'], 'fontFamily' => 'Oswald']]
                ]" 
            />
        </div>
    </div>
</div>