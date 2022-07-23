<?php 

require_once('../api/clients.php'); 

$client = new DBClients();

?>

<div class="home">
    <div class="container">
        <form class="querier" method="GET" id="querier" autocomplete="on">
            <input type="search" required name="search_input_client" value="<?php echo isset($_GET['query']) ? $_GET['query'] : ""?>" id="search_input" class="search_input" placeholder="Search client here (ex. Karl as first name or Sunogan for last name)" >
            <button type="submit" class="querier_btn">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <strong>
                            <i class="fa-solid fa-chart-bar me-1 text-success"></i>
                            Data Chart for Payment
                        </strong>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="chart_client"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    const chart_client = document.getElementById("chart_client");
    const ctx = chart_client.getContext("2d");
    let chart

    $('#querier').submit(function(e){
        e.preventDefault();
        chart.destroy()
        loadChartAJAX($('#search_input').val().toLowerCase(), "active");
    })

    $('#search_input').autocomplete({
        source: '../ajax/suggestions.php'
    })

    function loadChartAJAX (client_lname, client_payment_type = "active"){
        $.ajax({
            type: 'GET',
            url: '../ajax/loadPayments.php',
            data: {
                client_lname,
                client_payment_type
            },
            success: function(data) {

                data = JSON.parse(data)
                let i = 0
                let datax = {
                    client_name: data[0].client_name +" payment data",
                    payments: [...Object.keys(data).map(key => {
                                return {
                                    payment_amount: data[key].payment_amount,
                                    payment_date: data[key].payment_date,
                                }
                            })]
                }

                console.log(datax.payments[0])
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [ ...datax.payments.map(key => key.payment_date)],
                        datasets: [{
                            label: datax.client_name,
                            backgroundColor: '#FFA209',
                            borderWidth: 2,
                            fill: true,
                            data: [ ...datax.payments.map(key => parseFloat(key.payment_amount))]
                        }]
                    },
                    options: {
                        responsive: true,
                        intersect: false,
                        plugins: {
                            tooltip: {
                                backgroundColor: '#22242A',
                                titleColor: '#fff',
                                titleAlign: 'center',
                                bodyColor: '#fff',
                                bodySpacing: 8,
                                padding: 20,
                                titleFont: {
                                    font: 'Helvetica',
                                    size: 17,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    font: 'Helvetica',
                                    size: 14
                                },
                                titleFontSize: 20,
                                callbacks: {
                                    title: function (item) {
                                        return "Payment Details:"
                                    },
                                    label: function(item) {
                                        return "Client: " + item.dataset.label
                                    },
                                    afterLabel: function(item) {
                                        return "Amount of Payment: " + item.formattedValue
                                    },
                                    beforeBody: function(item) {
                                        return "Payment Date: " + item[0].label
                                    },
                                }
                            }
                        }
                    }
                });
            }
        });
    }

    loadChartAJAX();
</script>