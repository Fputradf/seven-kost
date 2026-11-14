@php
    use Filament\Support\Facades\FilamentAsset;

    $heading = $this->getHeading();
    $maxHeight = $this->getMaxHeight();
    $allCharts = $this->getAllChartsData();
@endphp

<x-filament-widgets::widget class="fi-wi-chart fi-wi-platform-analytics">
    <div
        x-data="{
            activeMetric: '{{ $activeMetric }}',
            chartType: '{{ $chartType }}',
            headings: {
                'revenue': 'Pendapatan & Komisi Platform',
                'bookings_kosts': 'Transaksi Sewa & Listing Kos',
                'marketplace': 'Aktivitas Produk Marketplace'
            },
            switchMetric(m) {
                this.activeMetric = m;
                if (m === 'revenue' && this.chartType !== 'bar') {
                    this.chartType = 'line';
                } else if (m !== 'revenue' && this.chartType !== 'line') {
                    this.chartType = 'bar';
                }
                this.$nextTick(() => window.dispatchEvent(new Event('resize')));
            },
            switchType(t) {
                this.chartType = t;
                this.$nextTick(() => window.dispatchEvent(new Event('resize')));
            }
        }"
        class="platform-analytics-card"
        style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.875rem; padding: 1.25rem 1.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03);"
    >
        {{-- Card Header: Title & Instant Client-Side Option Switchers (0ms Latency) --}}
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
            <h3 
                x-text="headings[activeMetric]"
                style="font-family: 'Geist', 'Plus Jakarta Sans', sans-serif; font-size: 1.125rem; font-weight: 700; color: #0f172a; margin: 0; letter-spacing: -0.02em;"
            >
                {{ $heading }}
            </h3>

            {{-- Segmented Option Buttons (Client-Side Instant Switching Tanpa Request Server) --}}
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                {{-- Opsi Pilihan Kategori Metrik --}}
                <div 
                    style="display: inline-flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.2rem; gap: 0.2rem;"
                    role="group" 
                    aria-label="Pilihan Data Analitik"
                >
                    <button
                        type="button"
                        @click="switchMetric('revenue')"
                        :style="activeMetric === 'revenue' 
                            ? 'padding: 0.35rem 0.75rem; border-radius: 0.375rem; font-family: Geist, sans-serif; font-size: 0.75rem; font-weight: 600; color: #ffffff; background: #031d44; border: none; cursor: pointer; transition: all 0.15s ease;' 
                            : 'padding: 0.35rem 0.75rem; border-radius: 0.375rem; font-family: Geist, sans-serif; font-size: 0.75rem; font-weight: 500; color: #475569; background: transparent; border: none; cursor: pointer; transition: all 0.15s ease;'"
                    >
                        Pendapatan &amp; Komisi
                    </button>

                    <button
                        type="button"
                        @click="switchMetric('bookings_kosts')"
                        :style="activeMetric === 'bookings_kosts' 
                            ? 'padding: 0.35rem 0.75rem; border-radius: 0.375rem; font-family: Geist, sans-serif; font-size: 0.75rem; font-weight: 600; color: #ffffff; background: #031d44; border: none; cursor: pointer; transition: all 0.15s ease;' 
                            : 'padding: 0.35rem 0.75rem; border-radius: 0.375rem; font-family: Geist, sans-serif; font-size: 0.75rem; font-weight: 500; color: #475569; background: transparent; border: none; cursor: pointer; transition: all 0.15s ease;'"
                    >
                        Sewa &amp; Listing Kos
                    </button>

                    <button
                        type="button"
                        @click="switchMetric('marketplace')"
                        :style="activeMetric === 'marketplace' 
                            ? 'padding: 0.35rem 0.75rem; border-radius: 0.375rem; font-family: Geist, sans-serif; font-size: 0.75rem; font-weight: 600; color: #ffffff; background: #031d44; border: none; cursor: pointer; transition: all 0.15s ease;' 
                            : 'padding: 0.35rem 0.75rem; border-radius: 0.375rem; font-family: Geist, sans-serif; font-size: 0.75rem; font-weight: 500; color: #475569; background: transparent; border: none; cursor: pointer; transition: all 0.15s ease;'"
                    >
                        Marketplace
                    </button>
                </div>

                {{-- Opsi Pilihan Tipe Grafik: Garis vs Batang --}}
                <div 
                    style="display: inline-flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.2rem; gap: 0.2rem;"
                    role="group" 
                    aria-label="Pilihan Tipe Visualisasi"
                >
                    <button
                        type="button"
                        @click="switchType('line')"
                        :style="chartType === 'line' 
                            ? 'padding: 0.35rem 0.7rem; border-radius: 0.375rem; font-family: Geist, sans-serif; font-size: 0.75rem; font-weight: 600; color: #ffffff; background: #0284c7; border: none; cursor: pointer; transition: all 0.15s ease;' 
                            : 'padding: 0.35rem 0.7rem; border-radius: 0.375rem; font-family: Geist, sans-serif; font-size: 0.75rem; font-weight: 500; color: #64748b; background: transparent; border: none; cursor: pointer; transition: all 0.15s ease;'"
                    >
                        Garis
                    </button>

                    <button
                        type="button"
                        @click="switchType('bar')"
                        :style="chartType === 'bar' 
                            ? 'padding: 0.35rem 0.7rem; border-radius: 0.375rem; font-family: Geist, sans-serif; font-size: 0.75rem; font-weight: 600; color: #ffffff; background: #0284c7; border: none; cursor: pointer; transition: all 0.15s ease;' 
                            : 'padding: 0.35rem 0.7rem; border-radius: 0.375rem; font-family: Geist, sans-serif; font-size: 0.75rem; font-weight: 500; color: #64748b; background: transparent; border: none; cursor: pointer; transition: all 0.15s ease;'"
                    >
                        Batang
                    </button>
                </div>
            </div>
        </div>

        {{-- Pre-rendered Canvases: Berpindah seketika (0ms) murni di browser via CSS display --}}
        <div style="position: relative; width: 100%; height: {{ $maxHeight }};">
            @foreach (['revenue', 'bookings_kosts', 'marketplace'] as $metricKey)
                @foreach (['line', 'bar'] as $typeKey)
                    <div
                        x-show="activeMetric === '{{ $metricKey }}' && chartType === '{{ $typeKey }}'"
                        x-cloak
                        wire:key="chart-{{ $metricKey }}-{{ $typeKey }}"
                        style="position: absolute; inset: 0; width: 100%; height: 100%;"
                    >
                        <div
                            x-load
                            x-load-src="{{ FilamentAsset::getAlpineComponentSrc('chart', 'filament/widgets') }}"
                            wire:ignore
                            data-chart-type="{{ $typeKey }}"
                            x-data="chart({
                                cachedData: @js($allCharts[$metricKey][$typeKey]['data']),
                                options: @js($allCharts[$metricKey][$typeKey]['options']),
                                type: @js($typeKey),
                            })"
                            class="fi-wi-chart-frame fi-wi-chart-canvas-ctn fi-wi-chart-frame-no-aspect-ratio"
                            style="height: 100%; width: 100%;"
                        >
                            <canvas
                                x-ref="canvas"
                                aria-hidden="true"
                                style="width: 100%; height: {{ $maxHeight }}; max-height: {{ $maxHeight }};"
                            ></canvas>

                            <span aria-hidden="true" x-ref="backgroundColorElement" class="fi-wi-chart-bg-color"></span>
                            <span aria-hidden="true" x-ref="borderColorElement" class="fi-wi-chart-border-color"></span>
                            <span aria-hidden="true" x-ref="gridColorElement" class="fi-wi-chart-grid-color"></span>
                            <span aria-hidden="true" x-ref="textColorElement" class="fi-wi-chart-text-color"></span>
                            <span aria-hidden="true" x-ref="tooltipBackgroundColorElement" class="fi-wi-chart-tooltip-bg-color"></span>
                            <span aria-hidden="true" x-ref="tooltipTextColorElement" class="fi-wi-chart-tooltip-text-color"></span>
                            <span aria-hidden="true" x-ref="tooltipBorderColorElement" class="fi-wi-chart-tooltip-border-color"></span>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
