<?php 

require_once('../api/clients.php'); 

$client = new DBClients();

?>

<div class="home">
    <div class="container">
        <h4 class="text-center mt-4"><strong>Dashboard</strong></h4>
        <hr/>
        <div class="row mt-3 first-row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card shadow-sm mb-2">
                    <div class="card-header bg-white text-center">
                        <strong>Total Clients</strong>
                    </div>
                    <div class="card-body text-center">
                        <h5 style="font-weight: bold;">
                            <i class="fa-solid fa-users me-1 text-primary"></i>
                            <?php echo $client->getClientsCount(''); ?>
                        </h5>
                    </div>
                </div>
                <div class="card shadow-sm mb-2">
                    <div class="card-header bg-white text-center">
                        <strong>Active Accounts/Payers</strong>
                    </div>
                    <div class="card-body text-center">
                        <h5 style="font-weight: bold;">
                            <i class="fa-solid fa-user-check me-1 text-info"></i>
                            <?php echo $client->getClientsCount('active'); ?>
                        </h5>
                    </div>
                </div>
                <div class="card shadow-sm mb-2">
                    <div class="card-header bg-white text-center">
                        <strong>Inactive/Closed Accounts</strong>
                    </div>
                    <div class="card-body text-center">
                        <h5 style="font-weight: bold;">
                            <i class="fa-solid fa-user-clock me-1 text-secondary"></i>
                            <?php echo $client->getClientsCount('closed'); ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card shadow-sm mb-2">
                    <div class="card-header bg-white text-center">
                        <strong>Current Year</strong>
                    </div>
                    <div class="card-body text-center">
                        <h5 style="font-weight: bold;">
                            <i class="fa-solid fa-calendar me-1 text-warning"></i>
                            <?php echo date('Y'); ?>
                        </h5>
                    </div>
                </div>
                <div class="card shadow-sm amount-card">
                    <div class="card-header bg-white">
                        <strong>Total of Amount of Payment for Year <?php echo date('Y');?></strong>
                        <i class="fa-solid fa-money-bill float-end text-success mt-1"></i>
                    </div>
                    <div class="card-body">
                        <h3 style="font-weight: bold;" class="text-success">
                            <span id="total_amount">0</span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-8 col-md-8 col-sm-12">
                <div class="card mb-4">
                    <div class="card-header bg-white text-center">
                        <strong>Payment Chart for the Year <?php echo date('Y'); ?></strong>
                    </div>
                    <div class="filterer">
                        <select name="filter" id="filter">
                            <option value="Whole" selected>Whole</option>
                            <option value="By Year">By Year</option>
                            <option value="Monthly">Monthly</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas class="chart" id="chart_client"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card bg-white mb-4 card-chart-address">
                    <div class="card-header bg-white text-center">
                        <strong><i class="fa-solid fa-map-marker-alt me-1 text-primary"></i>Address of other clients</strong>
                    </div>
                    <div class="card-body">
                        <canvas width="70" height="70" id="addresses_chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    const chart_client = document.getElementById("chart_client");
    const addresses_chart = document.getElementById("addresses_chart");
    const ctx = chart_client.getContext("2d");
    const ctx2 = addresses_chart.getContext("2d");

    let chart
    let chart2

    let colors=[
        '#FFA209', '#4E4949',
        '#42A1BE','#3399FF', 
        '#3366FF','#EDAC1A'
    ]
    let datax = {
        labels: [],
        datasets: [{
            label: [],
            data: [],
            backgroundColor: [...colors]
        }]
    }

    const chart_more = {
        borderColor: '#FFA209',
        borderWidth: 2,
        cubicInterpolationMode: 'monotone',
        pointHoverRadius: 6,
        pointRadius: 5,
        pointBorderWidth: 1,
        fill: true,
        lineTension: 0.2,
    }

    $('#search_input').autocomplete({
        source: '../ajax/suggestions.php'
    })

    function getSumOfPayments () {
        $.ajax({
            type: 'GET',
            url: '../ajax/loadSummaryPayments.php',
            success: (amount) => {
                $('#total_amount').text(parseFloat(amount).toLocaleString('en-US', {
                    style: 'currency',
                    currency: 'PHP'
                }))
            }, error: (err) => console.log(err)
        })
    }

    $('#filter').change(function(e){
        chart.destroy()

        if($(this).val() === "Whole"){
            loadChartWholePayments()
        } else if ($(this).val() === "Monthly"){
            loadChartMonthlyPayments()
        } else {
            loadChartAnnualPayments()
        }
    })

    function loadChartWholePayments (client_payment_type = "active"){
        $.ajax({
            type: 'GET',
            url: '../ajax/loadWholePayment.php',
            data: {
                client_payment_type
            },
            success: function(data) {
                data = JSON.parse(data)

               // console.log(data)

                let i = 0
                let datax = {
                    client_name: [...data.map(data => data.client_name)],
                    payments: [...Object.keys(data).map(key => {
                                return {
                                    payment_amount: data[key].payment_amount,
                                    payment_date: data[key].payment_date,
                                    payment_type: data[key].payment_type,
                                    mode_of_payment: data[key].mode_of_payment,
                                    payment_month: data[key].payment_month,
                                    payment_year: data[key].payment_year
                                }
                            })]
                }

       
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [...datax.payments.map(data => data.payment_date)],
                        datasets: [{
                            label: [new Date().getFullYear()],
                            mop: [ ...datax.payments.map(data => data.mode_of_payment)],
                            data: [ ...datax.payments.map(key => parseFloat(key.payment_amount))],
                            ...chart_more
                        }],
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
                                        return datax.client_name[item[0].dataIndex]
                                    },
                                    beforeLabel: function(item, data) {
                                        return item.data
                                    },
                                    label: function(item, data) {
                                        return " Amount of Payment: " + item.formattedValue
                                    },
                                    afterLabel: function(item, data) {
                                        return "Mode of Payment: " + item.dataset.mop[item.dataIndex]
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    }

    function loadChartAnnualPayments (client_payment_type = "active"){
        $.ajax({
            type: 'GET',
            url: '../ajax/loadChartAnnualPayments.php',
            data: {
                client_payment_type
            },
            success: function(data) {
                data = JSON.parse(data)
                data.map(datax => {
                    datax.years = [...new Set(datax.years)]
                    return datax
                })
                // console.log(data)

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [...data[0].years.map(year => year)],
                        datasets: [{
                            label: ["Monthly"],
                            data: [ data[0].total],
                            ...chart_more
                        }],
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
                                    size: 16,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    font: 'Helvetica',
                                    size: 14
                                },
                                titleFontSize: 20,
                                callbacks: {
                                    title: function (item) {
                                        console.log(item.label)
                                        return "Monthly Payment Details"
                                    },
                                    label: function(item, data) {
                                        return "Total Amount: " + item.formattedValue
                                    }
                                }
                            }
                        }
                    }
                })

                
            }
        });
    }

    function loadChartMonthlyPayments (client_payment_type = "active"){
        $.ajax({
            type: 'GET',
            url: '../ajax/loadChartMonthlyPayment.php',
            data: {
                client_payment_type
            },
            success: function(data) {
                data = JSON.parse(data)
                data.map(datax => {
                    datax.years = [...new Set(datax.dates)]
                    return datax
                })

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [...data[0].dates.map(date => date)],
                        datasets: [{
                            label: ["Monthly"],
                            data: [ data[0].total],
                            ...chart_more
                        }],
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
                                    size: 16,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    font: 'Helvetica',
                                    size: 14
                                },
                                titleFontSize: 20,
                                callbacks: {
                                    title: function () {
                                        return "Monthly Payment Details"
                                    },
                                    label: function(item, data) {
                                        return "Total Amount: " + item.formattedValue
                                    }
                                }
                            }
                        }
                    }
                })

                
            }
        });
    }

    function getRandomColor(){
        return colors[Math.floor(Math.random() * colors.length)];
    }

    function loadClientsAddresses(){
        $.ajax({
            type: 'GET',
            url: '../ajax/loadClientsAddresses.php',
            success(data){
                data= JSON.parse(data)
                let labels = { address: [] }
                let datax = []
                console.log(data)
                data.map((datax, i) => {
                    labels.address.push(datax.address)
                }) 

                data.forEach((item) => {
                    var existing = datax.filter(x => x.address === item.address)
                    if(existing.length){
                        var existingIndex = datax.indexOf(existing[0])
                        datax[existingIndex].clients = datax[existingIndex].clients.concat(item.clients)
                    } else {
                        if(typeof item.address == 'string')
                        item.clients= [item.clients];
                        datax.push(item)
                    }
                })

                console.log(datax)
               

                // let dataxes = {
                //     labels: [...Object.keys(data).map(datax => data[datax].address)],
                //     datasets: [
                //         ...data.map((datas, i) => {
                //             return [{
                //                 backgroundColor: [colors[i]],
                //                 data: [datas.clients.length],
                //                 label: [datas.address]
                //             }]
                //         })
                //     ]
                // }
                chart2 = new Chart(ctx2, {
                    type: 'pie',
                    data: {
                        labels: [...new Set(labels.address)],
                        datasets: [{
                            data: [...datax.map(d => d.clients.length)],
                            label: [...datax.map(d => d.address)],
                            backgroundColor: [...colors]
                        }],
                        fill: true
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            tooltip: {
                                backgroundColor: '#22242A',
                                bodyColor: '#fff',
                                bodySpacing: 8,
                                padding: 20,
                                bodyFont: {
                                    font: 'Helvetica',
                                    size: 14
                                },
                                titleFontSize: 20,
                                callbacks: {
                                    label: function(item){
                                        if(item.parsed === 1){
                                            return " "+item.formattedValue+" client in "+item.label ;
                                        }
                                        return " "+item.formattedValue+" clients in "+item.label
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error:(err) => console.log(err)
        });
    }
    loadClientsAddresses()
    loadChartWholePayments();
    getSumOfPayments()

</script>