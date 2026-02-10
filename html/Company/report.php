<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reports & Analytics</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* =========================
   GLOBAL STYLES
========================= */
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6fb;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: auto;
    padding: 30px;
}

/* =========================
   HEADER
========================= */
.page-title {
    font-size: 28px;
    margin-bottom: 5px;
}

.subtitle {
    color: #666;
    margin-bottom: 30px;
}

/* =========================
   STATS CARDS
========================= */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

.stat-card h3 {
    font-size: 14px;
    color: #777;
    margin-bottom: 10px;
}

.stat-value {
    font-size: 26px;
    font-weight: bold;
}

.stat-change {
    font-size: 13px;
}

.positive {
    color: #2ecc71;
}

.negative {
    color: #e74c3c;
}

/* =========================
   CHART CARD
========================= */
.chart-card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

.chart-placeholder {
    height: 250px;
    border-radius: 10px;
    background: linear-gradient(135deg, #dfe6fd, #eef1ff);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #555;
    font-weight: 600;
}

/* =========================
   STATUS SECTION
========================= */
.status-section {
    margin-bottom: 30px;
}

.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
}

.status-box {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

.status-box strong {
    font-size: 24px;
    display: block;
}

.status-box p {
    margin: 8px 0;
    color: #555;
}

.status-box span {
    font-size: 13px;
    color: #777;
}

/* =========================
   TABLE CARD
========================= */
.table-card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th, td {
    padding: 12px;
    text-align: left;
}

th {
    background: #f4f6fb;
    font-size: 14px;
    color: #555;
}

tr:nth-child(even) {
    background: #fafafa;
}

/* =========================
   EXPORT SECTION
========================= */
.export-section {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    text-align: center;
}

.export-section p {
    color: #666;
    margin-bottom: 15px;
}

.export-btn {
    border: none;
    padding: 12px 20px;
    margin: 8px;
    font-size: 14px;
    border-radius: 8px;
    cursor: pointer;
    color: #fff;
}

.export-btn.pdf {
    background: #e74c3c;
}

.export-btn.excel {
    background: #27ae60;
}

.export-btn i {
    margin-right: 6px;
}
</style>
</head>

<body>

<div class="container">

<h1 class="page-title">Reports & Analytics</h1>
<p class="subtitle">Overview of internship program performance</p>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Applications</h3>
        <div class="stat-value">284</div>
        <span class="stat-change positive">+18%</span>
    </div>

    <div class="stat-card">
        <h3>Avg. Time to Hire</h3>
        <div class="stat-value">12 days</div>
        <span class="stat-change negative">-3 days</span>
    </div>

    <div class="stat-card">
        <h3>Acceptance Rate</h3>
        <div class="stat-value">76%</div>
        <span class="stat-change positive">+5%</span>
    </div>

    <div class="stat-card">
        <h3>Intern Retention</h3>
        <div class="stat-value">92%</div>
        <span class="stat-change positive">+2%</span>
    </div>
</div>

<div class="chart-card">
    <h2>Applications Over Time</h2>
    <div class="chart-placeholder">Line Chart Placeholder</div>
</div>

<div class="status-section">
<h2>Application Status</h2>
<div class="status-grid">
    <div class="status-box"><strong>47</strong><p>Pending Review</p><span>17%</span></div>
    <div class="status-box"><strong>89</strong><p>Under Review</p><span>31%</span></div>
    <div class="status-box"><strong>52</strong><p>Interview Stage</p><span>18%</span></div>
    <div class="status-box"><strong>68</strong><p>Accepted</p><span>24%</span></div>
    <div class="status-box"><strong>28</strong><p>Rejected</p><span>10%</span></div>
</div>
</div>

<div class="table-card">
<h2>Performance by Department</h2>
<table>
<thead>
<tr>
<th>Department</th>
<th>Applications</th>
<th>Interviews</th>
<th>Hires</th>
<th>Success Rate</th>
<th>Rating</th>
</tr>
</thead>
<tbody>
<tr><td>Engineering</td><td>128</td><td>45</td><td>18</td><td>14%</td><td>⭐ 4.5</td></tr>
<tr><td>Marketing</td><td>67</td><td>28</td><td>12</td><td>18%</td><td>⭐ 4.3</td></tr>
<tr><td>Design</td><td>54</td><td>22</td><td>8</td><td>15%</td><td>⭐ 4.6</td></tr>
<tr><td>Data Analytics</td><td>35</td><td>15</td><td>6</td><td>17%</td><td>⭐ 4.4</td></tr>
</tbody>
</table>
</div>

<div class="export-section">
<h2>Export Reports</h2>
<p>Download comprehensive analytics reports</p>
<button class="export-btn pdf"><i class="fa-solid fa-file-pdf"></i> Export PDF</button>
<button class="export-btn excel"><i class="fa-solid fa-file-excel"></i> Export Excel</button>
</div>

</div>
</body>
</html>
