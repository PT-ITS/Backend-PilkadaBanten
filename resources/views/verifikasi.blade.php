<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        /* Ganti warna border menjadi merah saat ada kesalahan */
        .error {
            border: 1px solid red !important;
        }

        /* Ganti warna border menjadi hijau saat benar */
        .success {
            border: 1px solid green !important;
        }

        form{
            width:90%;
            margin:auto;
        }
    </style>
</head>
<body>
<div class="row">
        <div>
            <h2 class="text-center mt-3">Isi Data Untuk Aktivasi Aplikasi HMI</h2>
            <form method="POST" action="{{ route('aktivasi')}}" onsubmit="return validateForm()">
                @csrf <!-- Tambahkan token CSRF untuk keamanan -->
                <input type="hidden" id="id" name="id" value="{{$id}}">
        
                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" required class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="Password" oninput="checkPassword()">
                    <div id="tanggalMessage"></div> <!-- Tambahkan pesan kesalahan atau informasi di sini -->
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" required class="form-control" id="password" name="password" placeholder="Password" oninput="checkPassword()">
                    <div id="passwordMessage"></div> <!-- Tambahkan pesan kesalahan atau informasi di sini -->
                </div>
                <div class="mb-3">
                    <label for="rePassword" class="form-label">Retype password</label>
                    <input type="password" required class="form-control" id="rePassword" name="rePassword" placeholder="Retype password" oninput="checkPassword()">
                </div>
        
                <button type="submit" class="btn btn-primary float-end" id="submitButton" disabled>Submit</button>
            </form>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

<script>
    function checkPassword() {
        var password = document.getElementById("password").value;
        var rePassword = document.getElementById("rePassword").value;
        var submitButton = document.getElementById("submitButton");
        var passwordMessage = document.getElementById("passwordMessage");

        // Pengecekan panjang minimal 8 karakter
        if (password.length < 8) {
            passwordMessage.innerHTML = "Password harus memiliki minimal 8 karakter.";
            submitButton.disabled = true;
            return;
        } else {
            passwordMessage.innerHTML = "";
        }

        // Pengecekan apakah password memenuhi kriteria huruf, angka, dan karakter khusus
        // var regex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/;
        // if (!regex.test(password)) {
        //     passwordMessage.innerHTML = "Password harus terdiri dari huruf, angka, dan karakter khusus.";
        //     submitButton.disabled = true;
        //     return;
        // } else {
        //     passwordMessage.innerHTML = "";
        // }

        // Jika password dan retype password sama, ubah border menjadi hijau
        if (password === rePassword) {
            document.getElementById("password").classList.remove("error");
            document.getElementById("rePassword").classList.remove("error");
            document.getElementById("password").classList.add("success");
            document.getElementById("rePassword").classList.add("success");
            submitButton.disabled = false;
        } else {
            document.getElementById("password").classList.remove("success");
            document.getElementById("rePassword").classList.remove("success");
            document.getElementById("password").classList.add("error");
            document.getElementById("rePassword").classList.add("error");
            submitButton.disabled = true;
        }
    }

    function validateForm() {
        var password = document.getElementById("password").value;
        var rePassword = document.getElementById("rePassword").value;

        if (password !== rePassword) {
            alert("Password dan Retype Password harus sama!");
            return false;
        }
        return true;
    }
</script>
</body>
</html>
