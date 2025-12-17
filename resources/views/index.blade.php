@extends('base')
@section('title','Beranda')
@section('menuberanda', 'underline decoration-4 underline-offset-7')
@section('content')
    <section class="p-4 bg-white rounded-lg">
        <h1 class="text-3xl font-bold text-[#C0392B] mb-6 text-center">Statistik Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-600 font-medium">Total Pegawai</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $maleCount + $femaleCount }}</p>
                    </div>
                    <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-600 font-medium">Laki-laki</p>
                        <p class="text-2xl font-bold text-green-900">{{ $maleCount }}</p>
                    </div>
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>

            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-pink-600 font-medium">Perempuan</p>
                        <p class="text-2xl font-bold text-pink-900">{{ $femaleCount }}</p>
                    </div>
                    <svg class="w-12 h-12 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>


        <div class="mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-center">
                        <canvas id="chart1" class="w-full" style="max-height: 400px;"></canvas>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-center">
                        <canvas id="chart2" class="w-full" style="max-height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('plugins/chartjs-4/chart-4.5.0.js') }}"></script>
    <script>

        const ctx1 = document.getElementById('chart1');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ["Laki-laki", "Perempuan"],
                datasets: [{
                    label: 'Jumlah Pegawai',
                    data: [{{ $maleCount }}, {{ $femaleCount }}],
                    backgroundColor: [
                        '#3b82f6',
                        '#ec4899'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Pegawai Berdasarkan Gender',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = {{ $maleCount + $femaleCount }};
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });


        const ctx2 = document.getElementById('chart2');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($topPekerjaan as $p)
                        "{{ $p->nama }}"{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ],
                datasets: [{
                    label: 'Jumlah Pegawai',
                    data: [
                        @foreach($topPekerjaan as $p)
                            {{ $p->pegawai_count }}{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    ],
                    backgroundColor: '#C0392B',
                    borderColor: '#922B21',
                    borderWidth: 1,
                    borderRadius: 6,
                    barPercentage: 0.7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Top 5 Pekerjaan Dengan Jumlah Pegawai Terbanyak',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    },
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Jumlah: ' + context.parsed.y + ' pegawai';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return Number.isInteger(value) ? value : '';
                            }
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Pegawai',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Pekerjaan',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
