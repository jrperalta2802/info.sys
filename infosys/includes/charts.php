 <script>
    // First chart: Line chart
    const ctx1 = document.getElementById('myLineChart').getContext('2d');

    new Chart(ctx1, {
      type: 'line',
      data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June'],
        datasets: [{
          label: '# of Votes',
          data: [12, 19, 3, 5, 2, 3],
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          fill: true, // For area under the line to be filled
          borderWidth: 1
        }]
      },
      options: {
        responsive: true, // Makes the chart responsive
        maintainAspectRatio: true, // Maintains aspect ratio on resize
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    // Second chart: Bar chart
    const ctx2 = document.getElementById('myBarChart').getContext('2d');

    new Chart(ctx2, {
      type: 'bar',
      data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June'],
        datasets: [{
          label: '# of Sales',
          data: [65, 59, 80, 81, 56, 55],
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true, // Makes the chart responsive
        maintainAspectRatio: true, // Maintains aspect ratio on resize
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>