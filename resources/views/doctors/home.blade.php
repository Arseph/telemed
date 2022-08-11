@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
    	<div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{$totaltele}}</h3>
            <p>&nbsp; </p>

            <p>Total Teleconsultations</p>
          </div>
          <div class="icon">
            <i class="fas fa-handshake" style="margin-top: 10px;"></i>
          </div>
          <a href="#" class="small-box-footer"> </a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{$upcoming}}</h3>
            <p>&nbsp; </p>

            <p>Total Upcoming Teleconsultations</p>
          </div>
          <div class="icon">
            <i class="fas fa-location-arrow" style="margin-top: 10px;"></i>
          </div>
          <a href="#" class="small-box-footer"> </a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{$requested}}</h3>
            <p>&nbsp; </p>

            <p>Total Requested Teleconsultations</p>
          </div>
          <div class="icon">
            <i class="fas fa-arrow-alt-circle-down" style="margin-top: 10px;"></i>
          </div>
          <a href="#" class="small-box-footer"> </a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{$success}}</h3>
            <p>&nbsp; </p>

            <p>Total Completed Teleconsultations</p>
          </div>
          <div class="icon">
            <i class="fas fa-check-circle" style="margin-top: 10px;"></i>
          </div>
          <a href="#" class="small-box-footer"> </a>
        </div>
      </div>
    </div>
    <div class="box box-success">
        <div class="box-body">
            <div class="alert alert-jim">
		        <h3 class="page-header">Requested
		            <small>Teleconsultations</small>
		        </h3>
		        <canvas id="requestedTele" width="400" height="200"></canvas>
		        <h3 class="page-header">Completed
		            <small>Teleconsultations</small>
		        </h3>
		        <canvas id="completedTele" width="400" height="200"></canvas>
		    </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('public/plugin/Chart.js/Chart.min.js') }}"></script>
<script>
    <?php echo 'var url = "'.asset('home/chart').'";';?>
    var jim = [];
    $.ajax({
        url: url,
        type: 'GET',
        success: function(data) {
            jim = jQuery.parseJSON(data);
            var ctx = document.getElementById("requestedTele");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: jim.data1.months,
                    datasets: [{
                        label: '# of Requested Teleconsultations',
                        data: jim.data1.count,
                        backgroundColor: [
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 206, 86, 1)',
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
            var ctx = document.getElementById("completedTele");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: jim.data2.months,
                    datasets: [{
                        label: '# of Completed Teleconsultations',
                        data: jim.data2.count,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
        }
    });
</script>
@endsection