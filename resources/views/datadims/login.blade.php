<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#19d3d3;
}

/* Black top & bottom strip */
body::before,
body::after{
    content:"";
    position:fixed;
    left:0;
    width:100%;
    height:60px;
    background:black;
}

body::before{ top:0; }
body::after{ bottom:0; }

/* Container */
.login-container{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    text-align:center;
    padding:20px;
}

.login-box{
    width:100%;
    max-width:500px;
}

/* Title */
.login-box h1{
    font-size:60px;
    margin-bottom:15px;
}

/* Info text */
.info-text{
    color:red;
    font-size:16px;
    margin-bottom:40px;
}

/* Input style */
.input-group{
    border-bottom:2px solid rgba(0,0,0,0.3);
    margin-bottom:30px;
    position:relative;
}

.input-group input{
    width:100%;
    padding:15px;
    border:none;
    outline:none;
    background:transparent;
    text-align:center;
    font-size:18px;
}

/* Eye icon */
.eye{
    position:absolute;
    right:10px;
    top:50%;
    transform:translateY(-50%);
    font-size:20px;
    cursor:pointer;
}

/* Button */
.login-btn{
    margin-top:40px;
    width:60%;
    padding:12px;
    border:none;
    border-radius:15px;
    background:linear-gradient(to right,blue,purple);
    color:white;
    font-size:18px;
    cursor:pointer;
}

/* Mobile */
@media(max-width:600px){
    .login-box h1{
        font-size:40px;
    }
}
</style>
</head>

<body>

<div class="login-container">
    <div class="login-box">
        <h1>LOGIN</h1>

        @if($errors->any())
            <p class="info-text">
                {{ $errors->first() }}
            </p>
        @else
            <p class="info-text">
                isi username dan password dengan benar, jika tidak akun ini ke blok selama 1 jam
            </p>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" name="username" placeholder="ISI USERNAME" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="ISI PASSWORD" id="password" required>
                <span class="eye" onclick="togglePassword()">👁</span>
            </div>

            <button type="submit" class="login-btn">KLIK OK</button>
        </form>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.querySelector('.eye');
    if(passwordInput.type === "password"){
        passwordInput.type = "text";
        eyeIcon.textContent = "👁"; // berubah ikon saat show
    } else {
        passwordInput.type = "password";
        eyeIcon.textContent = "❌"; // kembali ke ikon awal
    }
}
</script>

</body>
</html>