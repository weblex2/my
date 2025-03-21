<!-- resources/views/maintainance/diskinfo.blade.php -->


<x-noppal>

    <div class="custom-container">
        <h1 class="title">Festplatteninformationen</h1>

        <div class="grid-container">
            @foreach($diskInfo as $disk)
                <div class="disk-card">
                    <h3 class="disk-title">{{ $disk['device'] }}</h3>
                    <canvas id="diskChart{{ $loop->index }}" width="250" height="250"></canvas>
                    <p class="disk-info">
                        <span class="font-semibold">Gesamtgröße:</span> {{ $disk['size'] }} |
                        <span class="font-semibold">Freier Speicher:</span> {{ $disk['free'] }}
                    </p>
                </div>

                <script>
                    const disk{{ $loop->index }} = {
                        labels: ['Belegt', 'Frei'],
                        datasets: [{
                            data: [
                                parseFloat('{{ $disk['size'] }}') - parseFloat('{{ $disk['free'] }}'),
                                parseFloat('{{ $disk['free'] }}')
                            ],
                            backgroundColor: ['#A0AEC0', '#68D391'],
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
