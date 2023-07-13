var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {

  type: 'bar',

data: { labels: [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
  ],

  datasets: [{
    
      label: 'My First dataset',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: [0, 10, 5, 2, 20, 30, 45]
    }]
  },
 

    options: {}
  
  });

//fetch from api
 async function getDummy() {
  const apiUrl = "http://dummy.restapiexample.com/api/v1/employees";
  const response = await fetch(apiUrl);
  const barChartData = await response.json();

  console.log(barChartData);
}
 
 getDummy();



