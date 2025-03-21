<!-- resources/views/maintainance/diskinfo.blade.php -->


<x-noppal>

    <div class="container p-8 mx-auto">
        <h1 class="mb-8 text-4xl font-semibold text-center text-blue-600">Festplatteninformationen</h1>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($diskInfo as $disk)
                <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-lg">
                    <h3 class="mb-4 text-xl font-medium text-gray-700">{{ $disk['device'] }}</h3>
                    <canvas id="diskChart{{ $loop->index }}" width="300" height="300"></canvas>
                    <p class="mt-4 text-sm text-gray-600">
                        <span class="font-semibold">Gesamtgröße:</span> {{ $disk['size'] }} |
                        <span class="font-semibold">Freier Speicher:</span> {{ $disk['free'] }}
                    </p>
                </div>

                <script>
                    const disk{{ $loop->index }} = {
                        labels: ['Belegt', 'Frei'],
                        datasets: [{
                            data: [
                                parseFloat('{{ $disk['size'] }}') - parseFloat('{{ $disk['free'] }}'), // Belegter Speicher
                                parseFloat('{{ $disk['free'] }}') // Freier Speicher
                            ],
                            backgroundColor: ['#A0AEC0', '#68D391'], // Sanfte Farben für benutzt und frei
                        }]
                    };

                    const ctx{{ $loop->index }} = document.getElementById('diskChart{{ $loop->index }}').getContext('2d');
                    new Chart(ctx{{ $loop->index }}, {
                        type: 'pie',
                        data: disk{{ $loop->index }},
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        font: {
                                            size: 14,
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            const dataset = tooltipItem.dataset;
                                            const total = dataset.data.reduce((a, b) => a + b, 0);
                                            const currentValue = dataset.data[tooltipItem.dataIndex];
                                            const percentage = ((currentValue / total) * 100).toFixed(2);
                                            return tooltipItem.label + ': ' + percentage + '%';
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>
            @endforeach
        </div>
    </div>


</x-noppal>
