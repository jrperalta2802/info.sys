<script>
    // Leader chart
const ctxLeader = document.getElementById('chart_leader').getContext('2d');
new Chart(ctxLeader, {
  type: 'bar',
  data: {
    labels: barangays, // Use barangays as labels
    datasets: [{
      label: '# of leaders',
      data: leaderCounts, // Use the leader counts
      backgroundColor: 'rgba(75, 192, 192, 0.2)',
      borderColor: 'rgba(75, 192, 192, 1)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    scales: {
      y: { beginAtZero: true }
    }
  }
});

// Barangay (vote) chart
const ctxBarangay = document.getElementById('chart_barangay').getContext('2d');
new Chart(ctxBarangay, {
  type: 'bar',
  data: {
    labels: barangays, // Use barangays as labels
    datasets: [{
      label: '# of votes',
      data: voteCounts, // Use the vote counts
      backgroundColor: 'rgba(255, 99, 132, 0.2)',
      borderColor: 'rgba(255, 99, 132, 1)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    scales: {
      y: { beginAtZero: true }
    }
  }
});

</script>