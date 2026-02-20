<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

/* ================= HEADER ================= */
.header{
    background:black;
    color:white;
    padding:15px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header-title{font-weight:bold;}
.header-right{display:flex;gap:20px;align-items:center;}

.header button{
    padding:6px 12px;
    cursor:pointer;
}

/* ================= LAYOUT ================= */
.container{display:flex;height:100vh;}

.sidebar{
    width:220px;
    background:black;
    color:white;
}

.sidebar ul{list-style:none;}

.sidebar li{
    padding:20px;
    border-bottom:1px solid #333;
    cursor:pointer;
    transition:all 0.3s ease;
    position:relative;
}

.sidebar li a {
    display: block;
    width: 100%;
    height: 100%;
    padding: 20px 10px; /* klik area lebih luas */
    color: inherit;
    text-decoration: none;
}

/* Hover effect */
.sidebar li:hover{
    background:#111;
    padding-left:30px;
}

/* Garis kiri animasi */
.sidebar li::before{
    content:"";
    position:absolute;
    left:0;
    top:0;
    width:0;
    height:100%;
    background:lime;
    transition:0.3s;
}

/* Active state */
.sidebar li.active{
    color:lime;
    background:#111;
    padding-left:30px;
}

.sidebar li.active::before{
    width:5px;
}

.content {
    flex: 1;
    background: #f2f2f2;
    padding: 40px;
    overflow-y: auto; /* pastikan scroll muncul vertikal */
    overflow-x: hidden;
}


.box{
    background:#d9d9d9;
}

/* ================= FORM ================= */
.top-form{
    background:#e5e5e5;
    padding:30px;
    margin-bottom:30px;
}

.top-title{
    text-align:center;
    font-size:20px;
    margin-bottom:25px;
    font-weight:bold;
}

.top-wrapper{
    display:flex;
    justify-content:space-between;
    gap:60px;
    flex-wrap:wrap;
}

.left-form{
    display:flex;
    flex-direction:column;
    gap:15px;
}

.left-form input{
    width:300px;
    padding:10px;
    background:#d3d3d3;
    border:none;
}

.ok-button{
    width:120px;
    padding:8px;
    background:lime;
    border:none;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.ok-button:hover{background:#00cc00;}

.upload-form{
    display:flex;
    flex-direction:column;
    gap:15px;
}

.upload-title{font-weight:bold;}

/* ================= TABEL ================= */

.data-table{
    width:100%;
    border-collapse:collapse;
    background:#d9d9d9;
}

.data-table th,
.data-table td{
    border:2px solid #999;
}

.data-table th{
    background:#c0c0c0;
    text-align:center;
    font-weight:bold;
    padding:20px;
}

.data-table td:first-child{
    padding:40px;
    width:50%;
    vertical-align:top;
}

.data-table td:last-child{
    width:50%;
    height:300px;
    padding:0;
}

.data-table img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

/* ================= RESPONSIVE ================= */
@media(max-width:768px){
    .sidebar{width:80px;}
    .sidebar li{font-size:12px;text-align:center;padding:15px 5px;}
    .header{flex-direction:column;gap:10px;align-items:flex-start;}
    .top-wrapper{flex-direction:column;gap:30px;}
}
</style>

<script>
function showList(listNumber) {
    document.querySelectorAll('.list-content')
        .forEach(div => div.style.display = 'none');

    document.getElementById('list'+listNumber)
        .style.display = 'block';

    document.querySelectorAll('.sidebar li')
        .forEach(li => li.classList.remove('active'));

    document.getElementById('sidebar-list'+listNumber)
        .classList.add('active');
    document.querySelectorAll('.sidebar li a').forEach(a => {
    a.addEventListener('click', function() {
        document.querySelectorAll('.sidebar li').forEach(li => li.classList.remove('active'));
        this.parentElement.classList.add('active');
    });
});    
}
</script>

</head>
<body>

<div class="header">
    <div class="header-title">WELCOME TO DASHBOARD BLOG DIMS</div>
    <div class="header-right">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
        <div>SELAMAT DATANG: {{ $username ?? 'User' }}</div>
    </div>
</div>

<div class="container">

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
   <ul>
        <li class="{{ Route::currentRouteName() == 'dasboard' ? 'active' : '' }}">
            <a href="{{ route('dasboard') }}" 
               style="color:inherit;text-decoration:none;display:block;">
               nyimpen kartu hasil studi
            </a>
        </li>

        <li class="{{ Route::currentRouteName() == 'dasboarddua' ? 'active' : '' }}">
            <a href="{{ route('dasboarddua') }}" 
               style="color:inherit;text-decoration:none;display:block;">
               nyimpen rekapstudi dan kartuujian
            </a>
        </li>
    </ul>
</div>

<!-- ================= CONTENT ================= -->
<div class="content">

<div id="list1" class="list-content" style="display:block;">
<div class="box">

<div class="top-form">
    <div class="top-title">isi nama, npm dan upload foto</div>

    <form action="{{ route('kartukhs.store') }}" 
          method="POST" 
          enctype="multipart/form-data">
        @csrf

        <div class="top-wrapper">

            <div class="left-form">
                <input type="text" name="nama" placeholder="isi nama............." required>
                <input type="text" name="npm" placeholder="isi npm............." required>
        
                <button type="submit" class="ok-button">SIMPAN</button>
            </div>

            <div class="upload-form">
                <div class="upload-title">upload foto semester</div>
                <input type="file" name="foto_semester" accept=".pdf" required>
            </div>

        </div>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Nama & NPM</th>
            <th>Foto KHS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dataSemester as $ds)
        <tr>
            <td>
                <p><strong>Nama:</strong> {{ $ds->nama }}</p>
                <br>
                <p><strong>NPM:</strong> {{ $ds->npm }}</p>
            </td>
            <td>
                @if($ds->foto_semester)
                    <a href="{{ asset('storage/'.$ds->foto_semester) }}" target="_blank">
                        {{basename($ds->foto_semester)}}
                  </a>
                @else
                    <div style="width:100%;height:100%;background:#ccc;"></div>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
</div>

<div id="list2" class="list-content" style="display:none;">
    <h2>Konten List 2</h2>
</div>

</div>
</div>

</body>
</html>