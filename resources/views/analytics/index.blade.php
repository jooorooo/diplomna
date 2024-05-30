<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>
<body>
<div class="container">
    <h1 class="my-4">Analytics Dashboard</h1>

    <div class="row">
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <h2 class="h5">Unique Visitors</h2>
                <canvas id="unique-visitors-chart"></canvas>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <h2 class="h5">Visits by Day</h2>
                <canvas id="visits-by-day-chart"></canvas>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <h2 class="h5">Visits by Product</h2>
                <canvas id="visits-by-product-chart"></canvas>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <h2 class="h5">Visits by Category</h2>
                <canvas id="visits-by-category-chart"></canvas>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <h2 class="h5">Visits by Brand</h2>
                <canvas id="visits-by-brand-chart"></canvas>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <h2 class="h5">Visits by Collection</h2>
                <canvas id="visits-by-collection-chart"></canvas>
            </div>
        </div>

        <div class="col-12">
            <h2 class="h5">Visits by Country</h2>
        </div>

        <div class="row">
            @foreach ($formattedData['visits_by_country'] as $country => $data)
                <div class="col-md-6 col-lg-3 mb-4">
                    <h3 class="h6">{{ $country }}</h3>
                    <canvas id="visits-by-country-chart-{{ $country }}"></canvas>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    const formattedData = @json($formattedData);

    function renderBarChart(chartId, labels, data, label) {
        var ctx = document.getElementById(chartId).getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    renderBarChart('unique-visitors-chart', formattedData.unique_visitors.labels, formattedData.unique_visitors.values, 'Unique Visitors');

    Object.keys(formattedData.visits_by_country).forEach(country => {
        renderBarChart('visits-by-country-chart-' + country, formattedData.visits_by_country[country].labels, formattedData.visits_by_country[country].values, 'Visits in ' + country);
    });

    renderBarChart('visits-by-day-chart', formattedData.visits_by_day.labels, formattedData.visits_by_day.values, 'Visits by Day');

    renderBarChart('visits-by-product-chart', formattedData.visits_by_product.labels, formattedData.visits_by_product.values, 'Visits by Product');

    renderBarChart('visits-by-category-chart', formattedData.visits_by_category.labels, formattedData.visits_by_category.values, 'Visits by Category');

    renderBarChart('visits-by-brand-chart', formattedData.visits_by_brand.labels, formattedData.visits_by_brand.values, 'Visits by Brand');

    renderBarChart('visits-by-collection-chart', formattedData.visits_by_collection.labels, formattedData.visits_by_collection.values, 'Visits by Collection');
</script>
</body>
</html>
