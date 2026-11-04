@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .filter-section form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group label {
        font-weight: 600;
        font-size: 14px;
        color: #334155;
    }

    .form-group select {
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 14px;
        background-color: #fdfefe;
        cursor: pointer;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-group select:focus {
        border-color: #2563eb;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, .25);
    }

    .chart-container {
        width: 100%;
        height: 450px;
        margin: 30px auto;
        padding: 25px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        position: relative;
    }

    .no-data {
        margin-top: 25px;
        padding: 16px 20px;
        background: #f8fafc;
        border-left: 4px solid #3b82f6;
        color: #475569;
        font-size: 15px;
        border-radius: 0 8px 8px 0;
    }
</style>

<h2 style="text-align: center; margin-bottom: 25px;">Detail Kinerja Keuangan Daerah</h2>

<div class="filter-section">
    <form action="{{ url('/kinerja-keuangan-detail') }}" method="GET">
        <div class="form-group">
            <label for="indikator">Pilih Indikator:</label>
            <select name="indikator" id="indikator" onchange="this.form.submit()">
                <option value="">-- Pilih Indikator --</option>
                @foreach ($indicators as $key => $value)
                    <option value="{{ $key }}" {{ $selected_indicator_key == $key ? 'selected' : '' }}>
                        {{ $value['title'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="start_year">Dari Tahun:</label>
            <select name="start_year" id="start_year" onchange="this.form.submit()">
                @foreach ($tahun_options as $year)
                    <option value="{{ $year }}" {{ $selected_start_year == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="end_year">Sampai Tahun:</label>
            <select name="end_year" id="end_year" onchange="this.form.submit()">
                @foreach ($tahun_options as $year)
                    <option value="{{ $year }}" {{ $selected_end_year == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="value_type">Tipe Tampilan:</label>
            <select name="value_type" id="value_type" onchange="this.form.submit()">
                <option value="persentase" {{ $selected_value_type == 'persentase' ? 'selected' : '' }}>Persentase</option>
                <option value="nominal" {{ $selected_value_type == 'nominal' ? 'selected' : '' }}>Nilai Nominal (Triliun)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="data_type">Jenis Nilai:</label>
            <select name="data_type" id="data_type" onchange="this.form.submit()">
                <option value="real" {{ $selected_data_type == 'real' ? 'selected' : '' }}>Realisasi</option>
                <option value="agr" {{ $selected_data_type == 'agr' ? 'selected' : '' }}>Anggaran</option>
                <option value="both" {{ $selected_data_type == 'both' ? 'selected' : '' }}>Anggaran & Realisasi</option>
            </select>
        </div>

        <input type="hidden" name="page" value="kinerja_keuangan_detail">
        <noscript><button type="submit">Terapkan Filter</button></noscript>
    </form>
</div>

@if ($selected_indicator_key && !empty($chart_datasets))
    <h2 style="margin-top: 30px; font-size: 20px; color: #1e293b;">Trend {{ str_replace('=', '-', $report_title) }}</h2>

    <div class="chart-container">
        <canvas id="myLineChart"></canvas>
    </div>

    <script>
        const chartLabels = {!! json_encode($chart_labels) !!};
        const chartDatasets = {!! json_encode($chart_datasets) !!};
        const yAxisLabel = "{{ $y_axis_label_for_chart }}";
        const valueType = "{{ $selected_value_type }}";

        const ctx = document.getElementById('myLineChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: chartDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            },
                            color: '#343a40'
                        }
                    },
                    title: {
                        display: true,
                        text: 'Analisis ' + '{{ str_replace("=", "-", $report_title) }}',
                        font: {
                            size: 18,
                            weight: 'bold'
                        },
                        color: '#0056b3'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    if (valueType === 'persentase') {
                                        label += context.parsed.y.toFixed(2) + '%';
                                    } else {
                                        label += context.parsed.y.toFixed(3) + ' T';
                                    }
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: yAxisLabel,
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#495057'
                        },
                        ticks: {
                            callback: function(value) {
                                if (valueType === 'persentase') {
                                    return value + '%';
                                } else {
                                    return value.toFixed(2);
                                }
                            },
                            font: {
                                size: 12
                            },
                            color: '#6c757d'
                        },
                        grid: {
                            color: '#e0e0e0'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tahun',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#495057'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#6c757d'
                        },
                        grid: {
                            color: '#e0e0e0'
                        }
                    }
                }
            }
        });
    </script>
@elseif ($selected_indicator_key && empty($chart_datasets))
    <p class="no-data">Tidak ada data yang tersedia untuk indikator dengan filter yang dipilih pada rentang tahun tersebut.</p>
@else
    <p class="no-data">Silakan pilih indikator dan filter untuk menampilkan grafik analisis.</p>
@endif
@endsection
