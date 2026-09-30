@props([
    'chartId',
    'chartOptions' => [],
])

<div 
    wire:ignore
    x-data="{
        chart: null,
        options: @js($chartOptions),
        init() {
            if (typeof ApexCharts === 'undefined') {
                console.error('ApexCharts is not available on window.');
                return;
            }

            this.$nextTick(() => {
                if (this.chart) {
                    this.chart.destroy();
                }
                this.chart = new ApexCharts(this.$refs.chartRef, this.options);
                this.chart.render();
            });

            window.addEventListener('update-chart-{{ $chartId }}', (event) => {
                if (this.chart && event.detail) {
                    if (event.detail.options) {
                        this.chart.updateOptions(event.detail.options, false, true);
                    }
                    if (event.detail.series) {
                        this.chart.updateSeries(event.detail.series, true);
                    }
                }
            });
        },
        destroy() {
            if (this.chart) {
                this.chart.destroy();
                this.chart = null;
            }
        }
    }"
    {{ $attributes->merge(['class' => 'w-full']) }}
>
    <div x-ref="chartRef"></div>
</div>